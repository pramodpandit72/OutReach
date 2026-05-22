<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Referral;
use App\Models\Campaign;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

/**
 * TelegramController
 *
 * Webhook handler for the Telegram Bot collabstack_bot.
 * Provides features like user registration linking, stats querying,
 * fetching active referral links, and conversational marketing consulting using Gemini AI.
 */
class TelegramController extends Controller
{
    /**
     * Handle incoming webhook requests from Telegram Bot API.
     */
    public function handleWebhook(Request $request)
    {
        $update = $request->all();

        if (empty($update) || !isset($update['message'])) {
            return response()->json(['status' => 'ignored']);
        }

        $message = $update['message'];
        $chatId = $message['chat']['id'] ?? null;
        $text = trim($message['text'] ?? '');
        $senderName = $message['from']['first_name'] ?? 'Friend';

        if (empty($chatId) || empty($text)) {
            return response()->json(['status' => 'ignored']);
        }

        Log::info("Telegram Bot received message from Chat ID: {$chatId}, text: {$text}");

        try {
            // Find the user by telegram chat ID
            $user = User::where('telegram_chat_id', $chatId)->first();

            // Generate the response text using the unified engine
            $reply = $this->generateResponse($text, $user, $senderName, $chatId, 'telegram');

            // Send response back to Telegram
            if (!empty($reply)) {
                $this->sendMessage($chatId, $reply);
            }
        } catch (\Exception $e) {
            Log::error("Error in Telegram Bot handleWebhook: " . $e->getMessage());
            $this->sendMessage($chatId, "⚠️ *An error occurred while processing your request.* Please try again later!");
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Handle incoming messages from the direct website chat widget.
     */
    public function handleWebChat(Request $request)
    {
        $text = trim($request->input('message') ?? '');
        
        if (empty($text)) {
            return response()->json(['error' => 'Message is empty'], 400);
        }

        // Get the active authenticated user on the web platform (if logged in)
        $user = auth()->user();
        $senderName = $user ? $user->name : 'Friend';

        try {
            // Generate the response using our unified engine
            $reply = $this->generateResponse($text, $user, $senderName, null, 'web');
            
            return response()->json([
                'status' => 'success',
                'reply' => $reply
            ]);
        } catch (\Exception $e) {
            Log::error("Error in handleWebChat: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'reply' => "⚠️ *An error occurred while processing your request.* Please try again later!"
            ]);
        }
    }

    /**
     * Generate bot response based on message text, user, and platform.
     */
    public function generateResponse($text, $user, $senderName, $chatId = null, $platform = 'telegram')
    {
        $text = trim($text);

        // 1. /start command
        if (str_starts_with($text, '/start')) {
            if ($platform === 'web') {
                return "🚀 *Welcome to OutReach Web Chat!* 🚀\n\n" .
                       "I am your automated peer-to-peer customer referral assistant. You can chat with me directly here! Ask me anything about referral marketing, check your stats, or get customized advice powered by Gemini AI.\n\n" .
                       "📋 *Available Commands:*\n" .
                       "📊 `/stats` - Check your active points, earned rewards, conversions count, and leaderboard rank.\n" .
                       "🔥 `/link` - Fetch your active referral links.\n\n" .
                       "💡 *Got a marketing question?* Just type any question (e.g. _How do I get more referrals?_) and I will provide expert suggestions instantly!";
            } else {
                return "🚀 *Welcome to OutReach Bot, {$senderName}!* 🚀\n\n" .
                       "I am your automated peer-to-peer customer referral assistant. You can use me to receive notifications, track your stats, or get expert marketing advice powered by Google Gemini AI!\n\n" .
                       "📋 *Available Commands:*\n" .
                       "🔗 `/register <your-email>` - Link your OutReach account with this Telegram bot to get instant transaction & reward alerts.\n" .
                       "📊 `/stats` - Check your active points, earned rewards, conversions count, and leaderboard rank.\n" .
                       "🔥 `/link` - Fetch your unique referral link to share directly on Telegram!\n\n" .
                       "💡 *Got a marketing question?* Just type any question (e.g. _How do I get more referrals?_) and I will provide expert suggestions instantly!";
            }
        }

        // 2. /register command
        if (str_starts_with($text, '/register')) {
            if ($platform === 'web') {
                return "💡 You are chatting directly on the OutReach website! " . 
                       ($user ? "You are logged in as *{$user->name}* ({$user->email})." : "To access your statistics and personal referral links, please log in to your account.") . "\n\n" .
                       "If you want to link your Telegram app to receive instant push alerts there, please open Telegram and message @collabstack_bot with: `/register " . ($user ? $user->email : "your-email") . "`";
            }

            $parts = explode(' ', $text, 2);
            $email = isset($parts[1]) ? trim($parts[1]) : '';

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return "❌ *Invalid Command Format.*\n\nPlease use: `/register your_email@example.com` to connect your account.";
            }

            $linkedUser = User::where('email', $email)->first();

            if (!$linkedUser) {
                return "❌ *Account Not Found.*\n\nWe couldn't find an OutReach account associated with the email address: *{$email}*. Please make sure you are registered on our web platform!";
            }

            // Link the telegram chat ID
            $linkedUser->telegram_chat_id = $chatId;
            $linkedUser->save();

            Log::info("Successfully linked Telegram Chat ID {$chatId} with user email {$email}");

            return "🎉 *Congratulations, {$linkedUser->name}!* 🎉\n\n" .
                   "Your Telegram account has been successfully linked with OutReach!\n\n" .
                   "🔔 You will now receive *instant push notifications* directly inside this chat whenever a friend registers through your referral link or you earn reward points/coupons!\n\n" .
                   "Try sending `/stats` to see your current campaign status.";
        }

        // 3. /stats command
        if (str_starts_with($text, '/stats')) {
            if (!$user) {
                return "🔑 *Account Not Connected.*\n\n" . 
                       ($platform === 'web' ? "Please log in to your OutReach account to view your statistics." : "Please link your OutReach account first by running: `/register <your-email>`");
            }

            $points = $user->points ?? 0;
            $conversionsCount = Referral::where('referrer_id', (string) $user->_id)
                ->where('status', 'converted')
                ->count();

            // Calculate Leaderboard Rank
            $topUsers = User::where('points', '>', 0)->orderBy('points', 'desc')->get();
            $rank = "Not Ranked";
            foreach ($topUsers as $index => $u) {
                if ((string)$u->_id === (string)$user->_id) {
                    $rank = "#" . ($index + 1);
                    break;
                }
            }

            // Fetch recent active rewards
            $rewardsCount = Reward::where('user_id', (string) $user->_id)->count();

            $badgesList = !empty($user->badges) ? implode(', ', $user->badges) : 'None yet 🚀';

            return "📊 *OutReach Advocate Statistics* 📊\n" .
                   "👤 *Name:* {$user->name}\n" .
                   "✉️ *Email:* {$user->email}\n\n" .
                   "🌟 *Reward Points:* `{$points}` points\n" .
                   "👥 *Successful Conversions:* `{$conversionsCount}` friends\n" .
                   "🏆 *Leaderboard Rank:* `{$rank}`\n" .
                   "🎁 *Rewards Claimed:* `{$rewardsCount}` rewards\n" .
                   "🏅 *Badges Earned:* _{$badgesList}_\n\n" .
                   "Keep sharing your referral link using `/link` to earn more rewards!";
        }

        // 4. /link command
        if (str_starts_with($text, '/link')) {
            if (!$user) {
                return "🔑 *Account Not Connected.*\n\n" . 
                       ($platform === 'web' ? "Please log in to your OutReach account to retrieve your referral links." : "Please link your OutReach account first by running: `/register <your-email>`");
            }

            // Find active campaigns that the customer can refer for
            $activeCampaigns = Campaign::where('status', 'active')
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->limit(3)
                ->get();

            if ($activeCampaigns->isEmpty()) {
                // General referral link
                $refLink = route('referral.handle', ['referralCode' => $user->referral_code]);
                return "🔥 *Your OutReach Referral Link:*\n\n" .
                       "Here is your primary link. Share this with your friends, and when they register, you will instantly earn rewards!\n\n" .
                       "👉 [Click to copy Link]({$refLink})\n`{$refLink}`";
            } else {
                $reply = "🔥 *Your Active Referral Links:*\n\n" .
                         "Here are the active campaigns you can share right now to earn premium points/coupons:\n\n";

                foreach ($activeCampaigns as $camp) {
                    $refLink = route('referral.handle', ['referralCode' => $user->referral_code]) . "?campaign=" . $camp->_id;
                    $rewardText = $camp->reward_type === 'points' ? "{$camp->reward_value} points" : "{$camp->reward_value} coupon";
                    $reply .= "🎁 *{$camp->title}* (Earn {$rewardText})\n" .
                              "👉 `{$refLink}`\n\n";
                }
                
                $reply .= "Copy any link above and forward it to your friends on Telegram, WhatsApp, or Twitter!";
                return $reply;
            }
        }

        // 5. General queries powered by Gemini API
        return $this->getGeminiConsultationResponse($text, $senderName);
    }

    /**
     * Consult the Google Gemini API to return advanced marketing advice.
     */
    private function getGeminiConsultationResponse($text, $senderName)
    {
        $prompt = "You are OutReach Bot, the official AI Growth & Platform Assistant for OutReach (also known as ReachRipple), a Social Media Based P2P Customer Referral & P2P Outreach Platform.\n\n" .
                  "--- OUTREACH PLATFORM CONTEXT ---\n" .
                  "- OutReach enables Business Owners (Business/Admin role) to create custom campaigns like 'Refer 3 friends and get a 20% discount' or set points/coupons.\n" .
                  "- Customers (Advocates role) get unique referral links (e.g. yoursite.com/ref/{code}) and share them via social media buttons (WhatsApp, X/Twitter, Facebook, LinkedIn).\n" .
                  "- When new friends register using a link, BOTH the advocate and friend receive rewards (points, badges, coupons) tracked on real-time dashboards & leaderboards.\n" .
                  "- Tech Stack: Laravel backend, MongoDB database, Tailwind CSS v4.2 styling, and Alpine.js frontend.\n\n" .
                  "--- COMMON TELEGRAM BOT USE CASES IN OUTREACH ---\n" .
                  "OutReach integrates this webhook-enabled Telegram Bot (@collabstack_bot) to support key automated workflows:\n" .
                  "1. Notifications & Real-Time Alerts: Instantly notify advocates on Telegram when friends sign up with their links, or forward support messages sent from the website directly to administrators on Telegram.\n" .
                  "2. Authentication: Simulates OTP login and account verification messages.\n" .
                  "3. AI Assistant Integration: Connects Gemini API directly to the Telegram / Web Chat widget so user inquiries receive smart replies.\n\n" .
                  "--- USER INQUIRY ---\n" .
                  "The user, {$senderName}, is asking: '{$text}'.\n\n" .
                  "--- INSTRUCTIONS ---\n" .
                  "Answer the query professionally. If it is about the OutReach platform, referral campaigns, Telegram features, or marketing/growth strategies, give precise, encouraging, and detailed answers incorporating the platform concepts. If it is a general question (e.g. greetings, general programming, general knowledge, math, science, or general business), answer accurately while staying in character as OutReach Bot.\n\n" .
                  "Keep the response professional, encouraging, highly actionable, and concise (max 4-5 sentences). Use bullet points where appropriate. Formatting MUST be basic Telegram Markdown (bold marked with a single asterisk like *bold*, italics like _italic_, inline code like `code`, lists like - list item). Do not use markdown headers (#) or double asterisks (**).";

        $apiKey = env('GEMINI_API_KEY');
        $replyText = null;

        if (!empty($apiKey)) {
            try {
                // Try package first
                if (class_exists(\Gemini\Laravel\Facades\Gemini::class)) {
                    $response = \Gemini\Laravel\Facades\Gemini::geminiPro()->generateContent($prompt);
                    $replyText = $response->text();
                }
            } catch (\Throwable $e) {
                Log::warning("Gemini SDK call failed in TelegramBot, trying direct HTTP call: " . $e->getMessage());
            }

            // Direct HTTP Fallback
            if (empty($replyText)) {
                try {
                    $httpResponse = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                        'contents' => [
                            ['parts' => [['text' => $prompt]]]
                        ]
                    ]);
                    if ($httpResponse->successful()) {
                        $replyText = $httpResponse->json('candidates.0.content.parts.0.text');
                    }
                } catch (\Exception $ex) {
                    Log::error("Direct Gemini API fallback failed: " . $ex->getMessage());
                }
            }
        }

        // If Gemini failed or was empty, provide a smart built-in fallback response
        if (empty($replyText)) {
            $replyText = "💡 *Top Outreach Tip:* To maximize your referral success, share your custom link in close-knit developer or marketing groups with a genuine personal note explaining how OutReach helps track social growth!\n\nLink your account with `/register <email>` to see your active referrals!";
        }

        // Clean up markdown bold markers that can crash Telegram parser if unmatched
        $replyText = str_replace('**', '*', $replyText);

        return $replyText;
    }

    /**
     * Send a styled Telegram message.
     */
    private function sendMessage($chatId, $text)
    {
        $token = env('TELEGRAM_BOT_TOKEN');

        if (empty($token)) {
            Log::warning("Telegram bot token not found in .env. Skipping message.");
            return;
        }

        try {
            Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'Markdown',
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to send HTTP Telegram message: " . $e->getMessage());
        }
    }
}

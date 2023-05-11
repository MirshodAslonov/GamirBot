<?php
date_default_timezone_set('Asia/Tashkent');
define('API_KEY','6129982851:AAEiCwff73L3Tkl_iWKaEqIS3wNPEYvS83A');
$admin = "5322552602";
$share_btn = [
    'share_btn' => "Do'stlarni taklif qilish 👭",
    'share_text' => "🤩🥳 Salom, biz o'yin boshladik siz ham bizga qo'shiling ?!",
    'share_link' => "https://t.me/game_mirshod_bot"
];
$comands = [
    [
        'commands' => json_encode([
            ["command" => "/info", "description" => "About bot."],
            ["command" => "/start", "description" => "Run the bot."],
            ["command" => "/startgame", "description" => "All available games."],
        ]),
        'scope' => json_encode([
            'type' => "chat",
            'chat_id' => $admin
        ])
    ],
    [
        'commands' => json_encode([
            ["command" => "/start", "description" => "Run the bot."],
            ["command" => "/startgame", "description" => "All available games."],
        ]),
        'scope' => json_encode([
            'type' => "all_private_chats"
        ])
    ]
];  
function bot($method = "getMe",$paramaters = []){
 $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $curl = curl_init();
    curl_setopt_array($curl,[
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER =>true,
        CURLOPT_POSTFIELDS =>$paramaters,
        CURLOPT_HTTPHEADER => ['Content-Type:multipart/form-data'],
    ]);    
    $res = curl_exec($curl);
    curl_close($curl);
    if(!curl_error($curl)) return json_encode(json_decode($res,true), JSON_PRETTY_PRINT);
};
{
    $update = json_decode(file_get_contents('php://input'),true);
    $chat_id = $update['message']['chat']['id']?$update['message']['chat']['id']:null;
    $chat_type = $update['message']['chat']['type']?$update['message']['chat']['type']:null;
    $user_name = $update['message']['from']['first_name']?$update['message']['from']['first_name']:null;
    $text = $update['message']['text']?$update['message']['text']:null;
    
    $call = $update['callback_query'];
    if ($call){
        $chat_id = $call['message']['chat']['id'];
    }
    $call_id = $call['id'];
    $call_game = $call['game_short_name'];
}
if($chat_type == "private"){
     if($text == "/start"){
        $hi_text = "Assalomu alaykum ".$user_name."<b><i> Gamir </i></b> botga hush kelibsiz.<pre>Bu botda siz turli xil aqlni charxlovchi 🤓\no'yinlar o'ynashingiz mumkun 🎯 \nboshlash uchun <b>Menu</b> dan /startgame buyrug'ini bosing 🤩</pre>";
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $hi_text,
            'parse_mode' => 'HTML' 
        ]);
    }else if($text == "/startgame"){
        $reply = "Quyida siz uchun tizimda mavjud barcha o'yinlar ruyxati keltirilgan, o'zingiz uchun istalgan o'yinni tanlang !";
        $game_keyboard = [
            [
                ['text' => "💡 Qanday o'naladi", 'callback_data' => "game||faq"],
                ['text' => "🎮 O'yinlar soni?", 'callback_data' => "game||count"],
            ],
            [  
                ['text' => " 🍏 Fruits card 🍏", 'callback_data' => "game||cards"],
            ]
        ];
        bot('sendmessage', [
            'chat_id' => $chat_id,
            'text' => $reply,
            'parse_mode' => 'HTML',
            'reply_markup' => json_encode([
                'inline_keyboard' => $game_keyboard
            ])
        ]);
    }else if($call_data == 'game||faq'){
            bot('answerCallbackQuery', [
                'callback_query_id' => $call_id,
                'show_alert' => true,
                'text' => "Ushbu game bot yordamida siz tanlagan o'yinni telegram ichida ochib o'ynashingiz va natijalarni tizim reytingeda kuzatishingiz mumkin."
            ]);
        }else if($call_data == 'game||count') {
            bot('answerCallbackQuery', [
                'callback_query_id' => $call_id,
                'cache_time' => 3600,
                'text' => "O'yinlar soni: ".count($games_list)
            ]);
        }else if($call_data == "game||cards") {
            bot('sendGame', [
                'chat_id' => $chat_id,
                'game_short_name' => "cards",
                'reply_markup' => json_encode([
                    'inline_keyboard' => [
                        [
                            ['text' => "🍏 Fruits card 🍏", 'callback_game' => []]
                        ],
                        [
                            [
                                'text' => $share_btn['share_btn'],
                                'url' => 'https://t.me/share/url?url='.$share_btn['share_link'].'&text='.$share_btn['share_text']
                            ]
                        ],
                    ]
                ])
            ]);
        }else if($call_game == "cards") {
            bot('answerCallbackQuery', [
                'callback_query_id' => $call_id,
                'url' => 
                "https://mproweb.uz/YTless/gameBot/games/cards/"
            ]);
        }else if($text == "/info" && $chat_id == $admin){
            bot('sendmessage', [
                'chat_id' => $chat_id,
                'text' => "Bu <b><i>Gamir</i></b> boti 🎮😁",
                'parse_mode' => 'HTML' 
            ]);
        }else if($text){
            $hi_text = "Iltimos ".$user_name."<b><i> Gamir </i></b> bot ni ishlatmoqchi  bo'lsangiz faqat <b><i>Menu</i></b> dan buyruqlarni yuboring ❗️";
        bot('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $hi_text,
            'parse_mode' => 'HTML' 
        ]);
    }
}

?>
<?php

namespace Acme\Users\Updates;

use Winter\Storm\Support\Facades\DB;
use Winter\Storm\Database\Updates\Seeder;

class SeedTranslatedMessagesTable extends Seeder
{
    public function run()
    {
        $messagesJson = file_get_contents("plugins/paveltopilin/blog/jsons/winter_translate_messages_202211212250.json");
        $messages = json_decode($messagesJson, true);
        foreach ($messages['winter_translate_messages'] as $message) {
            $translateMessage = DB::table('winter_translate_messages_copy')->insert([
                'id' => $message['id'],
                'code' => $message['code'],
                'message_data' => $message['message_data'],
                'found' => $message['found'],
                'code_pre_2_1_0' => $message['code_pre_2_1_0'],
            ]);
        }
    }
}

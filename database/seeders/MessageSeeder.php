<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $conversations = [
            // Conversación 1 (Usuario 1 y Usuario 2)
            [
                ['from' => 1, 'to' => 2, 'post' => 3, 'msg' => 'Hola, ¿el producto sigue disponible?'],
                ['from' => 2, 'to' => 1, 'post' => 3, 'msg' => 'Sí, aún está disponible. ¿Te interesa?'],
                ['from' => 1, 'to' => 2, 'post' => 3, 'msg' => 'Sí, ¿puedes enviarme más fotos?'],
                ['from' => 2, 'to' => 1, 'post' => 3, 'msg' => 'Claro, aquí tienes algunas imágenes.'],
                ['from' => 1, 'to' => 2, 'post' => 3, 'msg' => '¡Me interesa! ¿Podemos quedar mañana?'],
                ['from' => 2, 'to' => 1, 'post' => 3, 'msg' => 'Sí, en la tarde me viene bien.'],
            ],

            // Conversación 2 (Usuario 3 y Usuario 1)
            [
                ['from' => 3, 'to' => 1, 'post' => 7, 'msg' => 'Buenas, ¿haces envíos a otra ciudad?'],
                ['from' => 1, 'to' => 3, 'post' => 7, 'msg' => 'Sí, pero el envío corre por tu cuenta.'],
                ['from' => 3, 'to' => 1, 'post' => 7, 'msg' => 'Entiendo. ¿Cuánto pesa aproximadamente?'],
                ['from' => 1, 'to' => 3, 'post' => 7, 'msg' => 'Pesa unos 2kg, no debería ser muy costoso.'],
                ['from' => 3, 'to' => 1, 'post' => 7, 'msg' => 'Vale, lo pienso y te confirmo.'],
            ],

            // Conversación 3 (Usuario 2 y Usuario 3)
            [
                ['from' => 2, 'to' => 3, 'post' => 12, 'msg' => 'Hola, ¿puedes hacer un pequeño descuento?'],
                ['from' => 3, 'to' => 2, 'post' => 12, 'msg' => 'Depende, ¿cuánto estás ofreciendo?'],
                ['from' => 2, 'to' => 3, 'post' => 12, 'msg' => '¿Te parece bien 5€ menos?'],
                ['from' => 3, 'to' => 2, 'post' => 12, 'msg' => 'Mmm, te lo dejo en 3€ menos, ¿trato hecho?'],
                ['from' => 2, 'to' => 3, 'post' => 12, 'msg' => 'Vale, me parece bien. ¿Cuándo podemos vernos?'],
            ],
        ];

        foreach ($conversations as $conversation) {
            foreach ($conversation as $chat) {
                $message = new Message();
                $message -> userRemitent_id = $chat['from'];
                $message -> userDestination_id = $chat['to'];
                $message -> post_id = $chat['post'];
                $message -> fullMessage = $chat['msg'];
                $message -> isReaded = rand(0, 1);
                $message -> save();
            }
        }
    }
}

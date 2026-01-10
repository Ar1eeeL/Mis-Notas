<?php

namespace Database\Seeders;

use App\Models\Note;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $notes = [
            [
                'title' => 'Reunión con equipo de desarrollo',
                'content' => 'Revisar el progreso del sprint actual y planificar las tareas para la próxima semana. No olvidar traer el reporte de métricas.',
                'category' => 'trabajo',
                'priority' => 'high',
                'reminder_at' => Carbon::now()->addHours(2),
            ],
            [
                'title' => 'Comprar ingredientes para la cena',
                'content' => 'Tomates, cebolla, pasta, queso parmesano, albahaca fresca y aceite de oliva extra virgen.',
                'category' => 'personal',
                'priority' => 'normal',
                'reminder_at' => Carbon::now()->addHours(5),
            ],
            [
                'title' => 'Pagar facturas del mes',
                'content' => 'Electricidad, agua, internet y teléfono. Revisar que todos los montos sean correctos antes de pagar.',
                'category' => 'finanzas',
                'priority' => 'urgent',
                'reminder_at' => Carbon::now()->addDay(),
            ],
            [
                'title' => 'Estudiar para el examen',
                'content' => 'Capítulos 5 al 8 del libro de programación. Hacer los ejercicios prácticos y revisar las notas de clase.',
                'category' => 'estudio',
                'priority' => 'high',
                'reminder_at' => Carbon::now()->addDays(3),
            ],
            [
                'title' => 'Llamar al médico',
                'content' => 'Agendar cita para chequeo general anual. Preguntar por los resultados de los últimos análisis.',
                'category' => 'salud',
                'priority' => 'normal',
                'reminder_at' => Carbon::now()->addDays(2),
            ],
            [
                'title' => 'Idea para nuevo proyecto',
                'content' => 'Crear una aplicación de productividad que combine notas, tareas y calendario con inteligencia artificial para sugerencias automáticas.',
                'category' => 'ideas',
                'priority' => 'low',
                'reminder_at' => null,
            ],
            [
                'title' => 'Backup de archivos importantes',
                'content' => 'Hacer backup de todos los documentos del trabajo y fotos personales. Subir copia a la nube.',
                'category' => 'tecnología',
                'priority' => 'normal',
                'reminder_at' => Carbon::now()->addWeek(),
                'is_completed' => true,
            ],
        ];

        foreach ($notes as $note) {
            Note::create($note);
        }
    }
}

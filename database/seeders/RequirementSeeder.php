<?php

namespace Database\Seeders;

use App\Models\Requirement;
use Illuminate\Database\Seeder;

class RequirementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requirements = [
            [
                "title" => "Participar do Dia Mundial dos Desbravadores",
                "score" => 50,
                "type" => "pathfinder"
            ],
            [
                "title" => "Completar uma classe regular (Amigo, Companheiro, Pesquisador, etc)",
                "score" => 100,
                "type" => "pathfinder"
            ],
            [
                "title" => "Completar uma especialidade",
                "score" => 20,
                "type" => "pathfinder"
            ],
            [
                "title" => "Completar cinco especialidades no ano",
                "score" => 120,
                "type" => "pathfinder"
            ],
            [
                "title" => "Participar de um acampamento do clube",
                "score" => 80,
                "type" => "pathfinder"
            ],
            [
                "title" => "Participar de uma caminhada ou atividade na natureza",
                "score" => 40,
                "type" => "pathfinder"
            ],
            [
                "title" => "Participar de um projeto comunitário ou missionário",
                "score" => 70,
                "type" => "pathfinder"
            ],
            [
                "title" => "Participar de um Campori ou evento regional",
                "score" => 120,
                "type" => "unit"
            ],
            [
                "title" => "Ter presença mínima de 75% nas reuniões",
                "score" => 90,
                "type" => "unit"
            ],
            [
                "title" => "Estar com uniforme completo nas reuniões",
                "score" => 30,
                "type" => "unit"
            ],
            [
                "title" => "Memorizar o voto e a lei do desbravador",
                "score" => 25,
                "type" => "pathfinder"
            ],
            [
                "title" => "Leitura de um livro espiritual ou devocional",
                "score" => 35,
                "type" => "pathfinder"
            ],
            [
                "title" => "Participar de uma atividade missionária",
                "score" => 60,
                "type" => "unit"
            ],
            [
                "title" => "Participar de uma feira de especialidades ou feira de saúde",
                "score" => 50,
                "type" => "unit"
            ],
            [
                "title" => "Ajudar na organização de um evento do clube",
                "score" => 40,
                "type" => "unit"
            ]
        ];

        foreach ($requirements as $requirement) {
            Requirement::create($requirement);
        }
    }
}

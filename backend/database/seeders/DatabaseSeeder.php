<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Unidade;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $unidade = Unidade::create(
            [
                'nome' => "Facilita Certificadora - Matriz",
                'descritivo' => "",
                'codigo_unidade' => "MC",
                'cnpj_unidade' => "34178523000144",
                'cpf_titular' => "",
                'ie_unidade' => "105036250",
                'rg_titular' => "",
                'telefone_principal' => "",
                'telefone_secundario' => "",
                'email_principal' => "comercial@facilitacertificado.com.br",
                'tipo' => "gestor",
                'ativo' => true,
            ]
        );
        User::create(
            [
                'nome' => "Facilita Certificadora - Admin",
                'descritivo' => "",
                'email' => "comercial@facilitacertificado.com.br",
                'password' => Hash::make('f@cil'),
                'unidade_id' => $unidade->id,
                'e_admin' => true,
                'e_venda' => true,
                'e_compra' => true,
                'ativo' => true,
            ]
        );
    }
}

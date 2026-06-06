<?php

namespace App\Http\Controllers;

use App\Models\Classificacao;
use App\Models\Despesa;
use App\Models\Receita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ImportController extends Controller
{
    public function import()
    {
        return view('import');
    }

    public function importJson(Request $request){
        $dados = json_decode($request->conteudo_importacao, true);
    
        if($request->tipo_importacao === 'classificacao'){

            $rules = [
                'classificacoes' => 'required|array|min:1',
                'classificacoes.*.nome' => 'required|string|max:255',
                'classificacoes.*.slug' => 'required|string|max:255|distinct',
                'classificacoes.*.background_color' => [
                    'required', 
                    'string', 
                    'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'
                ],
            ];

            $messages = [
                // Validações do nó principal
                'classificacoes.required' => 'O nó principal "classificacoes" é obrigatório no JSON.',
                'classificacoes.array'    => 'O campo "classificacoes" precisa ser uma lista (array).',
                'classificacoes.min'      => 'O JSON precisa conter pelo menos 1 classificação para importação.',

                // Validações do campo 'nome'
                'classificacoes.*.nome.required' => 'O campo "nome" é obrigatório no item da posição :index.',
                'classificacoes.*.nome.string'   => 'O campo "nome" na posição :index precisa ser um texto válido.',
                'classificacoes.*.nome.max'      => 'O campo "nome" na posição :index não pode ter mais de 255 caracteres.',

                // Validações do campo 'slug'
                'classificacoes.*.slug.required' => 'O campo "slug" é obrigatório no item da posição :index.',
                'classificacoes.*.slug.string'   => 'O campo "slug" na posição :index precisa ser um texto válido.',
                'classificacoes.*.slug.max'      => 'O campo "slug" na posição :index não pode ter mais de 255 caracteres.',
                'classificacoes.*.slug.distinct' => 'O slug do item na posição :index está duplicado dentro do próprio arquivo JSON.',
                'classificacoes.*.slug.unique'   => 'O slug do item na posição :index já existe cadastrado no banco de dados.',

                // Validações do campo 'background_color'
                'classificacoes.*.background_color.required' => 'O campo "background_color" é obrigatório no item da posição :index.',
                'classificacoes.*.background_color.string'   => 'O campo "background_color" na posição :index precisa ser uma string.',
                'classificacoes.*.background_color.regex'    => 'A cor do item na posição :index precisa ser um código Hexadecimal válido (Ex: #FF5733).',
            ];

            $validator = Validator::make($dados, $rules, $messages);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $dadosValidados = $validator->validated();

            DB::transaction(function () use ($dadosValidados) {

                foreach($dadosValidados['classificacoes'] as $index => $classificacao){
                    Classificacao::create([
                        'nome' => $classificacao['nome'],
                        'slug' => $classificacao['slug'],
                        'background_color' => $classificacao['background_color'],
                        'user_id' => Auth::user()->id
                    ]);
                }

            });

            return redirect()->back()->with('success', 'Classificações importadas com sucesso!');

        } elseif($request->tipo_importacao === 'financeiro_mes'){
            
            $rules = [
                // Validação do bloco de Meses
                'meses'   => 'required|array|min:1',
                'meses.*' => 'required|string|date_format:Y-m',

                // Validação do bloco de Receitas
                'receitas'               => 'required|array',
                'receitas.*.nome'        => 'required|string|max:255',
                'receitas.*.valor'       => 'required|numeric|min:0',
                'receitas.*.ja_recebido' => 'required|string|in:sim,não',

                // Validação do bloco de Despesas
                'despesas'              => 'required|array',
                'despesas.*.nome'       => 'required|string|max:255',
                'despesas.*.valor'      => 'required|numeric|min:0',
                'despesas.*.recorrente' => 'required|string|in:sim,não',
                'despesas.*.ja_pago'    => 'required|string|in:sim,não',
            ];

            $messages = [
                // ERROS DO BLOCO: MESES
                'meses.required' => 'O bloco "meses" é obrigatório.',
                'meses.array'    => 'O campo "meses" deve ser uma lista.',
                'meses.min'      => 'É necessário informar pelo menos um mês.',
                'meses.*.required' => 'O mês na posição :index não pode estar vazio.',
                'meses.*.date_format' => 'O mês na posição :index deve estar no formato AAAA-MM (Ex: 2026-06).',

                // ERROS DO BLOCO: RECEITAS
                'receitas.required' => 'O bloco "receitas" é obrigatório.',
                'receitas.array'    => 'O campo "receitas" deve ser uma lista.',
                
                'receitas.*.nome.required' => 'O nome da receita na posição :index é obrigatório.',
                'receitas.*.nome.string'   => 'O nome da receita na posição :index deve ser um texto válido.',
                'receitas.*.nome.max'      => 'O nome da receita na posição :index não pode passar de 255 caracteres.',
                
                'receitas.*.valor.required' => 'O valor da receita na posição :index é obrigatório.',
                'receitas.*.valor.numeric'  => 'O valor da receita na posição :index precisa ser um número.',
                'receitas.*.valor.min'      => 'O valor da receita na posição :index não pode ser negativo.',
                
                'receitas.*.ja_recebido.required' => 'O campo "ja_recebido" na receita :index é obrigatório.',
                'receitas.*.ja_recebido.in'       => 'O campo "ja_recebido" na receita :index deve ser exatamente "sim" ou "não".',

                // ERROS DO BLOCO: DESPESAS
                'despesas.required' => 'O bloco "despesas" é obrigatório.',
                'despesas.array'    => 'O campo "despesas" deve ser uma lista.',
                
                'despesas.*.nome.required' => 'O nome da despesa na posição :index é obrigatório.',
                'despesas.*.nome.string'   => 'O nome da despesa na posição :index deve ser um texto válido.',
                'despesas.*.nome.max'      => 'O nome da despesa na posição :index não pode passar de 255 caracteres.',
                
                'despesas.*.valor.required' => 'O valor da despesa na posição :index é obrigatório.',
                'despesas.*.valor.numeric'  => 'O valor da despesa na posição :index precisa ser um número.',
                'despesas.*.valor.min'      => 'O valor da despesa na posição :index não pode ser negativo.',
                
                'despesas.*.recorrente.required' => 'O campo "recorrente" na despesa :index é obrigatório.',
                'despesas.*.recorrente.in'       => 'O campo "recorrente" na despesa :index deve ser exatamente "sim" ou "não".',
                
                'despesas.*.ja_pago.required' => 'O campo "ja_pago" na despesa :index é obrigatório.',
                'despesas.*.ja_pago.in'       => 'O campo "ja_pago" na despesa :index deve ser exatamente "sim" ou "não".',
            ];

            $validator = Validator::make($dados, $rules, $messages);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $dadosValidados = $validator->validated();
            
            DB::transaction(function () use ($dadosValidados) {
                foreach($dadosValidados['meses'] as $mes) {
                    foreach($dadosValidados['receitas'] as $receita) {
                        Receita::create([
                            'nome' => $receita['nome'],
                            'valor' => $receita['valor'],
                            'mes' => $mes,
                            'ja_recebido' => ($receita['ja_recebido'] === 'sim'),
                            'user_id' => Auth::user()->id
                        ]);
                    }
                    foreach($dadosValidados['despesas'] as $despesa) {
                        Despesa::create([
                            'nome' => $despesa['nome'],
                            'valor' => $despesa['valor'],
                            'mes' => $mes,
                            'recorrente' => ($despesa['recorrente'] === 'sim'),
                            'ja_pago' => ($despesa['ja_pago'] === 'sim'),
                            'user_id' => Auth::user()->id
                        ]);
                    }
                }
            });

            return redirect()->back()->with('success', 'Dados financeiros importados com sucesso!');

        } else {
            return redirect()->back()->with('error', 'Tipo de importação inválido.');
        }
    }
}

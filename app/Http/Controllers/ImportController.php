<?php

namespace App\Http\Controllers;

use App\Models\Cartao;
use App\Models\Classificacao;
use App\Models\Compra;
use App\Models\Conta;
use App\Models\Despesa;
use App\Models\Fatura;
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

                foreach($dadosValidados['classificacoes'] as $classificacao){
                    Classificacao::firstOrCreate(
                        ['slug' => $classificacao['slug']], 
                        
                        [
                            'nome' => $classificacao['nome'],
                            'background_color' => $classificacao['background_color'],
                            'user_id' => Auth::user()->id
                        ] 
                    );
                }

            });

            return redirect()->back()->with('success', 'Classificações importadas com sucesso!');

        } elseif($request->tipo_importacao === 'financeiro_mes'){
            
            $rules = [
                // Validação do bloco de Meses
                'mes'   => 'required|string|date_format:Y-m',

                // Validação do bloco de Contas
                'contas'                 => 'required|array|min:1',
                'contas.*.nome'          => 'required|string|max:255',
                'contas.*.tipo'          => 'required|string|max:255',
                'contas.*.saldo_inicial' => 'required|numeric',

                // Validação do bloco de Receitas (Aninhado em Contas)
                'contas.*.receitas'               => 'required|array',
                'contas.*.receitas.*.nome'        => 'required|string|max:255',
                'contas.*.receitas.*.valor'       => 'required|numeric|min:0',
                'contas.*.receitas.*.ja_recebido' => 'required|string|in:sim,não',

                // Validação do bloco de Despesas (Aninhado em Contas)
                'contas.*.despesas'                 => 'array',
                'contas.*.despesas.*.nome'          => 'required|string|max:255',
                'contas.*.despesas.*.valor'         => 'required|numeric|min:0',
                'contas.*.despesas.*.recorrente'    => 'required|string|in:sim,não',
                'contas.*.despesas.*.ja_pago'       => 'required|string|in:sim,não',
                'contas.*.despesas.*.classificacao' => 'sometimes|string|max:255|exists:classificacoes,slug',

                // Validação do bloco de Cartões (Aninhado em Contas - Opcional usando 'sometimes')
                'contas.*.cartoes'                            => 'sometimes|array',
                'contas.*.cartoes.*.nome'                     => 'required|string|max:255',
                'contas.*.cartoes.*.final_cartao'             => 'required|string|max:10',
                'contas.*.cartoes.*.fatura'                   => 'required',
                'contas.*.cartoes.*.faturas.*.ja_foi_pago'    => 'required|string|in:sim,não',
                'contas.*.cartoes.*.faturas.*.dia_fechamento' => 'required|string|max:2',
                'contas.*.cartoes.*.faturas.*.dia_vencimento' => 'required|string|max:2',

                // Validação de Compras (Aninhado em Faturas de Cartões)
                'contas.*.cartoes.*.faturas.*.compras'                  => 'array',
                'contas.*.cartoes.*.faturas.*.compras.*.descricao'      => 'required|string|max:255',
                'contas.*.cartoes.*.faturas.*.compras.*.data_compra'    => 'required|string|date_format:Y-m-d',
                'contas.*.cartoes.*.faturas.*.compras.*.valor'          => 'required|numeric|min:0',
                'contas.*.cartoes.*.faturas.*.compras.*.total_parcelas' => 'required|integer|min:1',
                'contas.*.cartoes.*.faturas.*.compras.*.numero_parcela' => 'required|integer|min:1',
                'contas.*.cartoes.*.faturas.*.compras.*.classificacao'  => 'sometimes|string|max:255|exists:classificacoes,slug',
            ];

            $messages = [
                // ERROS DO BLOCO: MESES
                'mes.required'      => 'O bloco "mes" é obrigatório.',
                'mes.date_format' => 'O mês deve estar no formato AAAA-MM (Ex: 2026-06).',

                // ERROS DO BLOCO: CONTAS
                'contas.required'                 => 'O bloco "contas" é obrigatório.',
                'contas.array'                    => 'O campo "contas" deve ser uma lista.',
                'contas.min'                      => 'É necessário informar pelo menos uma conta.',
                'contas.*.nome.required'          => 'O nome da conta é obrigatório.',
                'contas.*.tipo.required'          => 'O tipo da conta é obrigatório.',
                'contas.*.saldo_inicial.required' => 'O saldo inicial da conta é obrigatório.',
                'contas.*.saldo_inicial.numeric'  => 'O saldo inicial deve ser um valor numérico.',

                // ERROS DO BLOCO: RECEITAS
                'contas.*.receitas.required'        => 'O bloco "receitas" é obrigatório dentro da conta.',
                'contas.*.receitas.array'           => 'O campo "receitas" deve ser uma lista.',
                'contas.*.receitas.*.nome.required' => 'O nome da receita é obrigatório.',
                'contas.*.receitas.*.nome.max'      => 'O nome da receita não pode passar de 255 caracteres.',
                'contas.*.receitas.*.valor.required'=> 'O valor da receita é obrigatório.',
                'contas.*.receitas.*.valor.numeric' => 'O valor da receita precisa ser um número.',
                'contas.*.receitas.*.valor.min'     => 'O valor da receita não pode ser negativo.',
                'contas.*.receitas.*.ja_recebido.required' => 'O campo "ja_recebido" é obrigatório.',
                'contas.*.receitas.*.ja_recebido.in'       => 'O campo "ja_recebido" deve ser exatamente "sim" ou "não".',

                // ERROS DO BLOCO: DESPESAS
                'contas.*.despesas.required'        => 'O bloco "despesas" é obrigatório dentro da conta.',
                'contas.*.despesas.array'           => 'O campo "despesas" deve ser uma lista.',
                'contas.*.despesas.*.nome.required' => 'O nome da despesa é obrigatório.',
                'contas.*.despesas.*.nome.max'      => 'O nome da despesa não pode passar de 255 caracteres.',
                'contas.*.despesas.*.valor.required'=> 'O valor da despesa é obrigatório.',
                'contas.*.despesas.*.valor.numeric' => 'O valor da despesa precisa ser um número.',
                'contas.*.despesas.*.valor.min'     => 'O valor da despesa não pode ser negativo.',
                'contas.*.despesas.*.recorrente.required' => 'O campo "recorrente" na despesa é obrigatório.',
                'contas.*.despesas.*.recorrente.in'       => 'O campo "recorrente" deve ser exatamente "sim" ou "não".',
                'contas.*.despesas.*.ja_pago.required'    => 'O campo "ja_pago" é obrigatório.',
                'contas.*.despesas.*.ja_pago.in'          => 'O campo "ja_pago" deve ser exatamente "sim" ou "não".',

                // ERROS DO BLOCO: CARTÕES e FATURAS
                'contas.*.cartoes.array'                           => 'O campo "cartoes" deve ser uma lista.',
                'contas.*.cartoes.*.nome.required'                 => 'O nome do cartão é obrigatório.',
                'contas.*.cartoes.*.final_cartao.required'         => 'O final do cartão é obrigatório.',
                'contas.*.cartoes.*.fatura.required'              => 'O bloco de fatura do cartão é obrigatório.',
                'contas.*.cartoes.*.fatura.*.ja_foi_pago.required'=> 'O status de pagamento da fatura é obrigatório.',
                'contas.*.cartoes.*.fatura.*.ja_foi_pago.in'      => 'O status de pagamento da fatura deve ser "sim" ou "não".',
                'contas.*.cartoes.*.fatura.*.dia_fechamento.required' => 'O dia de fechamento da fatura é obrigatório.',
                'contas.*.cartoes.*.fatura.*.dia_vencimento.required' => 'O dia de vencimento da fatura é obrigatório.',

                // ERROS DO BLOCO: COMPRAS
                'contas.*.cartoes.*.fatura.*.compras.*.descricao.required'   => 'A descrição da compra é obrigatória.',
                'contas.*.cartoes.*.fatura.*.compras.*.data_compra.required' => 'A data da compra é obrigatória.',
                'contas.*.cartoes.*.fatura.*.compras.*.data_compra.date_format' => 'A data da compra deve seguir o formato AAAA-MM-DD.',
                'contas.*.cartoes.*.fatura.*.compras.*.valor.required'       => 'O valor da compra é obrigatório.',
                'contas.*.cartoes.*.fatura.*.compras.*.valor.numeric'        => 'O valor da compra deve ser um número.',
                'contas.*.cartoes.*.fatura.*.compras.*.valor.min'            => 'O valor da compra não pode ser negativo.',
                'contas.*.cartoes.*.fatura.*.compras.*.total_parcelas.required' => 'O total de parcelas é obrigatório.',
                'contas.*.cartoes.*.fatura.*.compras.*.total_parcelas.integer'  => 'O total de parcelas deve ser um número inteiro.',
                'contas.*.cartoes.*.fatura.*.compras.*.numero_parcela.required' => 'O número da parcela atual é obrigatório.',
                'contas.*.cartoes.*.fatura.*.compras.*.numero_parcela.integer'  => 'O número da parcela deve ser um número inteiro.',
            ];

            $validator = Validator::make($dados, $rules, $messages);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $dadosValidados = $validator->validated();
            
            DB::transaction(function () use ($dadosValidados) {
                foreach($dadosValidados['contas'] as $conta) {
                    $contaCriada = Conta::firstOrCreate(
                        [
                            'nome' => $conta['nome'],
                            'user_id' => Auth::user()->id
                        ],
                        [
                            'nome' => $conta['nome'],
                            'tipo' => $conta['tipo'],
                            'saldo' => $conta['saldo_inicial'],
                            'user_id' => Auth::user()->id
                        ]
                    );

                    foreach($conta['receitas'] as $receita) {
                        $receitaCriada = Receita::create(
                            [
                                'nome' => $receita['nome'],
                                'valor' => $receita['valor'],
                                'mes' => $dadosValidados['mes'],
                                'ja_recebido' => ($receita['ja_recebido'] === 'sim'),
                                'user_id' => Auth::user()->id,
                                'conta_id' => $contaCriada->id
                            ]
                        );

                        if($receitaCriada->ja_recebido){
                            $receitaCriada->conta->increment('saldo', $receitaCriada->valor);
                        }
                    }
                    
                    foreach($conta['despesas'] as $despesa) {
                        $despesaCriada = Despesa::create(
                            [
                                'nome' => $despesa['nome'],
                                'valor' => $despesa['valor'],
                                'mes' => $dadosValidados['mes'],
                                'recorrente' => ($despesa['recorrente'] === 'sim'),
                                'ja_pago' => ($despesa['ja_pago'] === 'sim'),
                                'user_id' => Auth::user()->id,
                                'conta_id' => $contaCriada->id,
                                'classificacao_id' => Classificacao::where('slug', $despesa['classificacao'])->first()->id
                            ]
                        );

                        if($despesaCriada->ja_pago){
                            $despesaCriada->conta->decrement('saldo', $despesaCriada->valor);
                        }
                    }

                    if (isset($conta['cartoes'])) {
                        foreach($conta['cartoes'] as $cartao){
                            $cartaoCriada = Cartao::firstOrCreate(
                                [
                                    'nome' => $cartao['nome'],
                                    'final_cartao' => $cartao['final_cartao']
                                ],
                                [
                                    'nome' => $cartao['nome'],
                                    'final_cartao' => $cartao['final_cartao'],
                                    'user_id' => Auth::user()->id
                                ]
                            );

                            $faturaCriada = Fatura::firstOrCreate(
                                [
                                    'mes_referencia' => $dadosValidados['mes'],
                                    'cartao_id' => $cartaoCriada->id
                                ],
                                [
                                    'mes_referencia' => $dadosValidados['mes'],
                                    'data_fechamento' => \Carbon\Carbon::createFromFormat('Y-m', $dadosValidados['mes'])->subMonth()->format('Y-m') 
                                        . '-' 
                                        . $cartao['fatura']['dia_fechamento'],
                                    'data_vencimento' => $dadosValidados['mes'] . '-' . $cartao['fatura']['dia_vencimento'],
                                    'cartao_id' => $cartaoCriada->id,
                                    'conta_id' => $contaCriada->id,
                                    'ja_foi_paga' => ($cartao['fatura']['ja_foi_pago'] === 'sim')
                                ]
                            );

                            foreach($cartao['fatura']['compras'] as $compra){
                                $compraCriada = Compra::create(
                                    [
                                        'descricao' => $compra['descricao'],
                                        'data_compra' => $compra['data_compra'],
                                        'valor' => $compra['valor'],
                                        'total_parcelas' => $compra['total_parcelas'],
                                        'numero_parcela' => $compra['numero_parcela'],
                                        'fatura_id' => $faturaCriada->id, 
                                        'classificacao_id' => Classificacao::where('slug', $compra['classificacao'])->first()->id
                                    ]
                                );

                                $faturaCriada->increment('despesa_total', $compraCriada->valor);
                            }

                            if($faturaCriada->ja_foi_paga){
                                $faturaCriada->conta()->decrement('saldo', $faturaCriada->despesa_total);
                            }
                        }
                    }

                    
                }
            });

            return redirect()->back()->with('success', 'Dados financeiros importados com sucesso!');

        } else {
            return redirect()->back()->with('error', 'Tipo de importação inválido.');
        }
    }
}

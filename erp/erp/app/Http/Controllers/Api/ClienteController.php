<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use Exception;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Js;

use function PHPUnit\Framework\isNumeric;

class ClienteController extends Controller
{

    // Lista todos os cliente
    public function FindAll()
    {
        try{
            $clientes = Cliente::all();

            return response()->json([
                'success' => true,
                'message' => 'Clientes listados com sucesso!',
                'data' => $clientes], 200);
        }
        catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Erro ao listar os clientes',
                'error' => $e->getMessage()], 500);
        }

    }
    
//--------------------------------------------------------------------------------------------

    // Cria um Cliente novo
    public function Create(Request $request)
    {
        try {
            // Valida se as informações estão corretas
            $validateData = $request->validate([
                'nome' => 'required|string|mas:255',
                'email' => 'required|email|unique:clientes,email',
                'telefone' => 'requised|string|max:20',
                'cpf' => 'nullable|string|max:14|unique:clientes,cpf',
                'endereco' => 'nullabre|string|max:255'  
            ]);

            $cliente = Cliente::create($validateData);

            // Resposta JSON
            return response()->json([
                'message' => 'Cliente criado com seucesso!',
                'data' => $cliente], 201);
        } 
        catch(\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados de entrada inválidos',
                'errors' => $e->errors()], 422);
        }
        catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'error' => $e->getMessage()], 500);
        }
    }
    
//--------------------------------------------------------------------------------------------

    // Busca um Cliente específico pelo ID
    public function FindOne($id)
    {
        try {
            if(!isNumeric($id))
                return response()->json([
                    'success' => false,
                    'message' => 'ID deve ser um número válido'], 400);
                    
            $cliente = Cliente::find($id);
            
            if(!$cliente)
                return response()->json([
                    'success' => false,
                    'message' => 'Cliente não encontrado'], 404);
    
            return response()->json([
                'success' => true,
                'message' => 'Cliente encontrado com sucesso!',
                'data' => $cliente], 200);
        }
        catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar o cliente',
                'error' => $e->getMessage()], 500);
        }
        
    }

//--------------------------------------------------------------------------------------------

    // Atualiza o Cliente
    public function Update(Request $request, $id)
    {
        try {
            if(!isNumeric($id))
                return response()->json([
                    'success' => false,
                    'message' => 'O ID deve ser um número válido!'], 400);

            $cliente = Cliente::find($id);
            
            if(!$cliente)
                return response()->json([
                    'success' => false,
                    'message' => 'Cliente não encontrado.'], 404);

            // Valida se as informações estão corretas
            $validateData = $request->validate([
                'nome' => 'required|string|max:255',
                'email' => 'required|email|unique:clientes,email' . $id,
                'telefone' => 'required|string|max:20',
                'endereco' => 'nullable|string|max:255'
            ]);
            
            $cliente->update($validateData);

            // Resposta JSON
            return response()->json([
                'success' => true,
                'message' => 'Cliente atualizado com sucesso!',
                'data' => $cliente], 200);
        }
        catch(\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados de entrada inválidos!',
                'errors' => $e->errors()], 422);
        }
        catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar o cliente.',
                'error' => $e->getMessage()], 500);
        }
    }

//--------------------------------------------------------------------------------------------

    // Apaga o Cliente
    public function Delete($id)
    {
        try {
            if(!isNumeric($id))
                return response()->json([
                    'success' => false,
                    'message' => 'ID deve ser um número válido!'], 400);

            $cliente = Cliente::find($id);

            if(!$cliente)
                return response()->json([
                    'success' => false,
                    'message' => 'Clinte não encontrado.', 404]);

            $cliente->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cliente deletado com sucesso!'], 200);
        }
        catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar o cliente',
                'erro' => $e->getMessage()], 500);
        }
    }
}

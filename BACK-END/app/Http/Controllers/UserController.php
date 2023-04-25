<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;





class UserController extends Controller
{
   
    //La función toma la solicitud del usuario como un objeto de solicitud de Laravel ($request).
    public function register(Request $request)
    {
        //Luego, extrae los datos de la solicitud como un array utilizando el método "all()" del objeto de solicitud.
        $data = $request->json()->all();
        $itExistsUserName=User::where('email',$data['email'])->first();
        
/*select * from uers where email="; La función luego realiza una consulta en la base de datos
 para verificar si ya existe un usuario con el correo electrónico proporcionado por el usuario. */
        if ($itExistsUserName==null) {
            $user = User::create(
                [
                    'name'=>$data['name'],
                    'email'=>$data['email'],
                    'password'=>Hash::make($data['password'])

                ]
            );
            /*se genera un token de autenticación para el usuario recién creado utilizando el método "createToken()"
              del modelo de usuario y se devuelve como parte de la respuesta de la función.*/
            $token = $user->createToken('web')->plainTextToken;
                return response()->json([
                    'data'=>$user,
                    'token'=> $token

                ],200);// tiempo de respuesta, si excede marca un error
        } else {
               return response()->json([
                'data'=>'User already exists!',
                'status'=> false
            ],200);
       }

   }

     public function login(Request $request){

        if(!Auth::attempt($request->only('email','password')))
        {
            
            return response()->json
            ([
                'message'=> 'Correo o contraseña incorrectos',
                'status'=> false
            ],400);
        }
         $user = User::where('email',$request['email'])->firstOrFail();
         $token = $user->createToken('web')->plainTextToken;
    
         return response()->json
         ([
            'data'=> $user,
            'token'=>$token
         ]);
    
       }

   public function logout(Request $request)
   {
    $request->user()->currentAccessToken()->delete();
    return response()->json
    ([
        'status'=> true,
    ]);

   }

    public function showById($id)
    {
        $user = User::find($id);
        
        return response()->json(["data"=>$user]);
    }


    public function newPassword($correoElectronico)
    {
        $usuario = User::where('email', $correoElectronico)->first();
        if (!$usuario) 
        {
            return response()->json(['message' => 'El usuario no existe'], 200);
        }
        else
        {
        $nuevaPassword = Str::random(6);
        
        $usuario->password = Hash::make($nuevaPassword);
        $usuario->save();
        
        return response()->json([
            'mensaje' => 'Contraseña actualizada!!!',
            'nueva_password' => $nuevaPassword,
            
        ], 200);
        }

        
    }   
    
  
}

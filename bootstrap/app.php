<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            // Auth Middlewares
            'check.token'           => \App\Http\Middleware\Auth\CheckToken::class,
            'check.active'          => \App\Http\Middleware\Auth\CheckUserActive::class,
            'check.verify.token'    => \App\Http\Middleware\Auth\CheckVerificationToken::class,
            
            // Workspace & Team Middlewares
            'check.admin'           => \App\Http\Middleware\Workspace\CheckWorkspaceAdmin::class,
            'check.ws_member'       => \App\Http\Middleware\Workspace\CheckWorkspaceMember::class,
            'check.team'            => \App\Http\Middleware\Team\CheckTeamAccess::class,
            'check.team_member'     => \App\Http\Middleware\Team\CheckTeamMember::class,

            // Channel Middlewares
            'check.ch_admin'        => \App\Http\Middleware\Channel\CheckChannelAdmin::class,
            'check.channel.join'    => \App\Http\Middleware\Channel\CheckChannelJoin::class,
            'check.channel.leave'   => \App\Http\Middleware\Channel\CheckChannelLeave::class,
            
            // Message & GridFS Middlewares
            'check.channel.member'  => \App\Http\Middleware\Message\CheckChannelMember::class,
            'check.message.owner'   => \App\Http\Middleware\Message\CheckMessageOwnership::class,
            'check.file.access'     => \App\Http\Middleware\Message\CheckFileAccess::class,
            'handle.gridfs'         => \App\Http\Middleware\Message\HandleGridFSUpload::class,
          
            'check.direct.chat' => \App\Http\Middleware\Message\CheckDirectChatAccess::class,
       
       
            ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        
        // 1. Validation Errors
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Validation Failed',
                    'errors'  => $e->errors()
                ], 422);
            }
        });

        // 2. 404 Resource Not Found
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'The requested resource or route was not found.'
                ], 404);
            }
        });

        // 3. 403 Forbidden
        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'You do not have permission to perform this action.'
                ], 403);
            }
        });

        // 4. Generic/Server Errors
        // Yahan \Throwable use kiya hai warning se bachne ke liye
        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status'  => 'error',
                    'message' => $e->getMessage() ?: 'Internal Server Error',
                ], 500);
            }
        });

    })->create();
@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between w-full p-8">
        <div>
            <h1 class="text-3xl font-bold text-foreground">Desbravadores</h1>
            <p class="text-muted-foreground mt-1">Gerencie os membros do clube</p>
        </div>

        <a class="flex items-center bg-primary text-primary-foreground h-10 px-4 rounded-lg" href="#">
            <i data-lucide="user-plus" class="mr-2 h-4 w-4"></i>
            <span>Novo Cadastro</span>
        </a>
    </div>
@endsection

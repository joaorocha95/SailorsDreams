@extends('layouts.app')

@section('content')

@section('title','About')

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

<!-- Styles -->
<style>
    .aboutSection {
        margin-left: 40px;
        margin-right: 40px;
        text-align: justify;
    }
</style>

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item active">About Us</li>
</ol>

<h2 style="margin-left:40px; text-align:center;">About Us</h2>
<div class="aboutSection">
    O objetivo do projeto DreamSailor é o desenvolvimento de uma online shop de embarcações de luxo onde os utilizadores têm a possibilidade de experienciar as embarcações dos seus sonhos. Os clientes terão a possibilidade de comprar ou alugar a sua embarcação favorita. As empresas parceiras terão, então, a oportunidade de criar páginas online dos seus produtos nesta plataforma e, assim, expandir o seu negócio para o mercado online.
    <br>
    <br>
    Existem 6 user profiles diferentes, sendo eles Guest, User, Client, Seller, Support e Admin.
    <br>
    <br>
    O Guest terá acesso à homepage, poderá pesquisar os produtos utilizando varias métricas para os filtrar, visitar páginas de produtos, visualizar os respetivos reviews, tem acesso a FAQs, pode contactar a Help Desk e pode ainda registar-se na plataforma.
    <br>
    <br>
    Um User, além de ter todas as permissões de um Guest, tem direito a uma página de perfil e a editar esta mesma, criar e modificar a sua lista de desejos, comprar/alugar um produto, adicionar produtos ao seu carrinho de compras e edtar este mesmo, tem acesso a contactos de vendedores, pode fazer denuncias de fraude, e ainda candidatar-se a vendedor.
    <br>
    <br>
    O client terá todas as capacidades e permissões do user, podendo ainda, ter acesso a páginas de estado do pedido, dar review dos produtos que comprou ou alugou e dos vendedores com quem fez negócio e também pode ver a classificação que lhe foi atribuida.
    <br>
    <br>
    O seller terá todas as capacidades e permissões do cliente, podendo ainda, criar paginas de produto, alterar as próprias paginas de produto, faz review aos clientes com quem fez negócio e atualizar estado do pedido, e tambem contactar este.
    <br>
    <br>
    As contas classificados como help desk tem todas as permissões de um guest, mas estão responsáveis por fornecer suporte aos utilizadores, e podem modificar o FAQ ou adicionar novas questões.
    <br>
    <br>
    As contas de Admin servem como administradores do site, possuindo todas as capacidades dos tipos de contas anteriores mas com acesso a uma área de administracao que permite banir ou suspender qualquer outro tipo de conta, caso estas infrinjam alguma regra, e modificar ou apagar paginas do site, incluindo, paginas de venda e review, estao tambem responsaveis por avaliar as candidaturas a parceiro;
</div>

@endsection
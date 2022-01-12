@extends('layouts.app')

@section('content')
<title>FAQ</title>

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

<!-- Styles -->
<style>
    .everything {
        align-self: center;
        text-align: center;
    }

    .test {
        font-family: 'Nunito', sans-serif;
        background-color: #343434;
        color: #B9B9B9;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
    }

    .produtoDestaque {
        position: relative;
        display: inline-block;
        background-color: white;
        height: 600px;
        width: 1300px;
    }

    .img__description {
        display: flex;
        align-items: center;
        justify-content: center;

        position: absolute;
        height: 100%;
        width: 100%;

        top: 0;
        bottom: 0;
        left: 0;
        right: 0;

        background: rgba(208, 222, 235, 0.6);
        color: #343434;
        visibility: hidden;
        opacity: 0;

        /* transition effect. not necessary */
        transition: opacity .3s, visibility .2s;
    }

    .produtoDestaque:hover .img__description {
        visibility: visible;
        opacity: 1;
    }

    .produtoDestaque>img {
        width: 100%;
        height: 100%;
    }

    .multiplosDestaques {
        display: inline-block;
    }

    .multiplosDestaquesItems {
        position: relative;
        display: inline-block;
        background: white;
        width: 350px;
        height: 500px;
        margin-left: 40px;
        margin-right: 40px;
        margin-bottom: 50px;
    }

    .multiplosDestaquesItems:hover .img__description {
        visibility: visible;
        opacity: 1;
    }

    .multiplosDestaquesItems>img {
        width: 100%;
        height: 100%;
    }
</style>


<audio autoplay id="myAudio">
    <source src="home.ogg" type="audio/ogg">
</audio>
<script>
    document.getElementById("myAudio").volume = 0.2;
</script>
<div class="everything">
    <h3>DESTAQUE</h3>
    <div class="produtoDestaque">
        <img alt="Destaque" src="https://scontent.fopo2-1.fna.fbcdn.net/v/t1.18169-1/p200x200/18664317_1456148631095681_8921032841732140123_n.jpg?_nc_cat=111&ccb=1-5&_nc_sid=7206a8&_nc_ohc=M-v9k0XVwMQAX9GgxV6&_nc_ht=scontent.fopo2-1.fna&oh=00_AT8e6Ny0GlD3oMevMNkLI0Bd7ZKEIxwPr66uwmQxFnnGEw&oe=62031120">
        <div class="produtoDestaque img__description">
            This image looks super neat.
        </div>
    </div>

    <div style="height: 100px;">
    </div>

    <div class="multiplosDestaques">
        <div class="multiplosDestaquesItems">
            <img alt="Destaque1" src="https://scontent.fopo2-1.fna.fbcdn.net/v/t1.18169-1/p200x200/18664317_1456148631095681_8921032841732140123_n.jpg?_nc_cat=111&ccb=1-5&_nc_sid=7206a8&_nc_ohc=M-v9k0XVwMQAX9GgxV6&_nc_ht=scontent.fopo2-1.fna&oh=00_AT8e6Ny0GlD3oMevMNkLI0Bd7ZKEIxwPr66uwmQxFnnGEw&oe=62031120">
            <div class="img__description">
                This image looks meh.
            </div>
        </div>
        <div class="multiplosDestaquesItems">
            <img alt="Destaque2" src="https://scontent.fopo2-1.fna.fbcdn.net/v/t1.18169-1/p200x200/18664317_1456148631095681_8921032841732140123_n.jpg?_nc_cat=111&ccb=1-5&_nc_sid=7206a8&_nc_ohc=M-v9k0XVwMQAX9GgxV6&_nc_ht=scontent.fopo2-1.fna&oh=00_AT8e6Ny0GlD3oMevMNkLI0Bd7ZKEIxwPr66uwmQxFnnGEw&oe=62031120">
            <div class="img__description">
                This image looks decent.
            </div>
        </div>
        <div class="multiplosDestaquesItems">
            <img alt="Destaque3" src="https://scontent.fopo2-1.fna.fbcdn.net/v/t1.18169-1/p200x200/18664317_1456148631095681_8921032841732140123_n.jpg?_nc_cat=111&ccb=1-5&_nc_sid=7206a8&_nc_ohc=M-v9k0XVwMQAX9GgxV6&_nc_ht=scontent.fopo2-1.fna&oh=00_AT8e6Ny0GlD3oMevMNkLI0Bd7ZKEIxwPr66uwmQxFnnGEw&oe=62031120">
            <div class="img__description">
                This image looks good.
            </div>
        </div>
        <div class="multiplosDestaquesItems">
            <img alt="Destaque4" src="https://scontent.fopo2-1.fna.fbcdn.net/v/t1.18169-1/p200x200/18664317_1456148631095681_8921032841732140123_n.jpg?_nc_cat=111&ccb=1-5&_nc_sid=7206a8&_nc_ohc=M-v9k0XVwMQAX9GgxV6&_nc_ht=scontent.fopo2-1.fna&oh=00_AT8e6Ny0GlD3oMevMNkLI0Bd7ZKEIxwPr66uwmQxFnnGEw&oe=62031120">
            <div class="img__description">
                This image looks awesome.
            </div>
        </div>
    </div>
</div>


@endsection
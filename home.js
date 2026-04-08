
const texto= "alalalalla";
const elemento=document.getElementById("texto");
let i=0;
let conteudo = ['a', 'b', 'c']; //tenta fazer com uma array o texto

function escrever() {
    if (i < texto.length){
        elemento.innerHTML += texto.charAt(i);
        i++;
        setTimeout(escrever, 50);
    } else{
        //mostra os botoes depois que termina
        document.getElementById("botoes").style.opacity=1;
    }
}
console.log("rodando");
const texto= "Bem vindo ao site Isumagi! Deseja fazer login ou cadastrar-se?";
let i=0;
const velocidade=40;

function escrever() {
    if (i < texto.length){
        document.getElementById("texto").innerHTML += texto.charAt(i);
        i++;
        setTimeout(escrever, velocidade);
    } else{
        //mostra os botoes depois que termina
        document.getElementById("botoes").style.opacity=1;
    }
}
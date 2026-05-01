const audio = document.getElementById("bulbiAudio");

  function ativarAudio() {
    audio.muted = false;
    audio.play();
  }

  document.addEventListener("click", ativarAudio, { once: true });
  if("click"){
    ativarAudio();  
  }


const texto= "Bem vindo ao Isumagi! Deseja fazer login ou cadastrar-se, querido usuário?";
let posicao = 0;
const botoes = document.getElementById("botoes");

function escrever() {
   const el= document.getElementById("texto");

   if(!el){
    console.log("Elemento não encontrado");
    return;
   }

   el.innerHTML = texto.slice(0, posicao);
   posicao++;
   if(posicao<=texto.length){
    setTimeout(escrever, 50);
   }
   if(posicao>texto.length){
    document.getElementById("botoes").style.display = "block";
    botoes.style.opacity = 1;
    }
   }
window.onload = function(){
    escrever();
}
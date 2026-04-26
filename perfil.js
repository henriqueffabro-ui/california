//function pfp() {
    
  //  const form = document.getElementById('formPfp');
    //fileInput.addEventListener('submit', function(e) {
      //  e.preventDefault(); // Evita o envio padrão do formulário
    //});

    //const formData = new FormData(form);

    //fetch('perfil.php', {
      //  method: 'POST',
        //body: formData
    //})
    //.then(response => response.text())
    //.then(data => {
      //  document.getElementById("fotoPerfil").src = data.caminho;
    //});
//}
function postarResposta(comentario_id) {

    let input = document.getElementById("resposta-" + comentario_id);
    let textoResposta = input.value;

        if (textoResposta.trim() === "") return;
        fetch("resposta.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "conteudo=" + encodeURIComponent(textoResposta) + "&parent_id=" + encodeURIComponent(comentario_id) //encodeURIComponent é usado para garantir que o texto da resposta seja enviado corretamente, mesmo que contenha caracteres especiais
        })
        .then(res => res.json()) //pega a resposta do servidor e converte para JSON (bagulho de JS)
        .then(data => { //pega a respota do servidor, que é o conteúdo da resposta, e insere na página

        
            let container = document.getElementById("Aparecerresposta-" + comentario_id);

            container.innerHTML += `
            <div class="tudoderesosta">
            <p class="resposta_para">Resposta para <strong>${data.nomePai}</strong>:</p>
            
            <div class="userinfo">
            <img src='${data.foto_perfil}' alt='Foto de perfil' class='pfpimgComent'>
            <span class="nome_resposta">${data.nome}</span>
            </div>

            <p class="resposta_texto">
            <span class="nomePai_resposta">
            <a href="#comentario-${data.parent_id}">
                @${data.nomePai}
            </a>
            </span> ${data.conteudo}</p>
            
            <button class="upvotarcoment" onclick="votarcoment(${data.id}, 1)">
                        <img width="15px" src="imgs/arrow.webp">
                    </button>

                    <span id="upvotescoment-${data.id}">${data.upvotes ?? 0}</span>

                    <button class="downvotarcoment" onclick="votarcoment(${data.id}, -1)">
                        <img width="15px" src="imgs/arrowd.jpg">
                    </button>

                    <span id="downvotescoment-${data.id}">${data.downvotes ?? 0}</span>

                    <div id="Aparecerresposta-${data.id}"></div>
            </div>`;

            

            
            // limpa input
            input.value = "";
            
            
        });

};
function postarComentario(post_id) {

    let input = document.getElementById("comentario-" + post_id);
    let texto = input.value;

        fetch("coment.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "post_id=" + post_id + "&comentario=" + encodeURIComponent(texto) //encodeURIComponent é usado para garantir que o texto do comentário seja enviado corretamente, mesmo que contenha caracteres especiais
        })
        .then(res => res.json())
        .then(data => {
            let html = `<div class="comentario">

                    <div class="userinfo">
                    <img src='${data.foto_perfil}' alt='Foto de perfil' class='pfpimgComent'>
                    <span class="nome_comentario">${data.nome}</span>
                    </div>

                    <p class="comentario">${data.comentario}</p>
                    
                    
                    <button class="upvotarcoment" onclick="votarcoment(${data.id}, 1)">
                        <img width="15px" src="imgs/arrow.webp">
                    </button>

                    <span id="upvotescoment-${data.id}">${data.upvotes ?? 0}</span>

                    <button class="downvotarcoment" onclick="votarcoment(${data.id}, -1)">
                        <img width="15px" src="imgs/arrowd.jpg">
                    </button>

                    <span id="downvotescoment-${data.id}">${data.downvotes ?? 0}</span>

                    <br>

                    
                    
            </div>
            `;


            document.getElementById("comentarios-" + post_id).innerHTML += html;
                
            //document.getElementById("downvotes-" + post_id).innerText = data.down;

            input.value = ""; // limpa o campo
        });

};

function votar(post_id, tipo) {

        fetch("vote.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "post_id=" + post_id + "&tipo=" + tipo
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById("upvotes-" + post_id).innerText = data.up;
            document.getElementById("downvotes-" + post_id).innerText = data.down;
        });

};

let upvotes = 0; // Variável para armazenar o número de upvotes, inicializada com o valor do banco de dados
let downvotes = 0;
let votedUp = false;
let votedDown = false;

let imagens = [];

function adicionarImagem() {
    let input = document.getElementById("fileInput");

    for (let i = 0; i < input.files.length; i++) {
        let file = input.files[i];
        imagens.push(file);

        // cria container (li)
        let li = document.createElement("li");

        // cria imagem
        let img = document.createElement("img");
        img.src = URL.createObjectURL(file);
        img.style.width = "200px";
        img.classList.add("previewImagens");

        li.appendChild(img);


        document.getElementById("listaImgs").appendChild(li);
    }

    input.value = "";
}

function postar() {
    
   
    let dados = new FormData();

    imagens.forEach((img) => {
        dados.append("imagens[]", img);
    });

    // adiciona os outros campos manualmente
    dados.append("titulo", document.getElementById("TituloPost").value);
    dados.append("descricao", document.getElementById("DescPost").value);

        for (let pair of dados.entries()) {
    console.log(pair[0], pair[1]);
}

        fetch("postar.php", {
            method: "POST",
            
            body: dados //encodeURIComponent é usado para garantir que o texto do post seja enviado corretamente, mesmo que contenha caracteres especiais
        })
        .then(res => res.json()) //pega a resposta do servidor e converte para JSON (bagulho de JS)
        .then(data => { //pega a respota do servidor, que é o conteúdo do post, e insere na página

                let imagensHTML = "";

                    data.imagens.forEach(img => {
                        imagensHTML += `<img class ="imgspost" src="uploads/${img}" width="200">`;
                });
            
            let html = `<div class="postagem" id="postagem-${data.id}">
                <div></div>
    
                    <div class="posts">
                            <div class="userinfo">
                            <img src='${data.foto_perfil}' alt='Foto de perfil' class='pfpimgPost'>
                            <span class='card-user'>${data.nome}</span>
                            </div>
                            <h5 class='card-title'>${data.titulo}</h5>
                            <p class='card-text'>${data.descricao}</p>
                            
                            ${imagensHTML}
                    </div>
                </div>
                <br>
            </div>
            `;
              document.getElementById("posts")
            .insertAdjacentHTML("beforeend", html);

            document.getElementById("formPost").reset();
            document.getElementById("listaImgs").innerHTML = "";
            imagens = [];

            
        });

}

function mostrarInputResposta(id) {
    let div = document.getElementById("resposta-" + id);
    div.style.display = "block";
}

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
            <h6 class="nome_resposta">${data.nome}</h6>

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

function votarcoment(comentario_id, tipo) {
    
        fetch("vote_coment.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "comentario_id=" + comentario_id + "&tipo=" + tipo
        })
        .then(res => res.json())
        .then(data => {
            
            document.getElementById("upvotescoment-" + comentario_id).innerText = data.upcoment;
            document.getElementById("downvotescoment-" + comentario_id).innerText = data.downcoment;
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
                    <h6 class="nome_comentario">${data.nome}</h6>

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
                    <input id="resposta-${data.id}" placeholder="Responda..."> 
                    <button onclick="postarResposta(${data.id})">Postar</button>
                    <br>

                    
                    <p style="color: #a9a9a9; font-size: 12px;">
                    --------------------------------------------------------------------------------------------------------
                    </p>
            </div>
            `;


            document.getElementById("comentarios-" + post_id).innerHTML += html;
                
            //document.getElementById("downvotes-" + post_id).innerText = data.down;

            input.value = ""; // limpa o campo
        });

};

function votarUp() {
    if (!votedUp && !votedDown) {
        upvotes = upvotes + 1;
        alert(upvotes);
        votedUp = true;
    }
    else if (votedDown && !votedUp) {
        downvotes = downvotes - 1;
        upvotes = upvotes + 1;
        votedDown = false;
        votedUp = true;
        alert(upvotes);
    }
    else if (votedUp) {
        upvotes = upvotes - 1;
        alert(upvotes);
        votedUp = false;

    }
}

function votarDown() {
    if (!votedDown && !votedUp) {
        downvotes = downvotes + 1;
        alert(downvotes);
        votedDown = true;
    }
    else if (votedUp && !votedDown) {
        upvotes = upvotes - 1;
        downvotes = downvotes + 1;
        votedUp = false;
        votedDown = true;
        alert(downvotes);
    }
    else if (votedDown) {
        downvotes = downvotes - 1;
        alert(downvotes);
        votedDown = false;
    }
}



function mostrarImg(){ //ao clicar no botão de img, aciona o input, que por sua vez aciona essa função
            
                

    const input = document.getElementById('fileInput'); //pega o elemento input

            //const nome = input.files[0]?.name || "Nenhum arquivo selecionado"; mostra o nome da img na tela, 
            //document.getElementById('nomeArquivo').innerText = nome;           mas pode ser util no banco de dados para salvar o nome da img, caso queira fazer isso futuramente

    const imgpreview = document.getElementById('imgpreview'); //pega a div onde ficam as imgs na tela

    imgpreview.innerHTML = "";

    for(let i = 0; i < input.files.length; i++){ //percorre todas as imgs
        const arquivo = input.files[i]; //pega a img atual, de acordo com o índice do for
            
        if(arquivo.type.startsWith("image/")){ //verifica se o arquivo é img

            const img = document.createElement("img"); //cria uma tag img
            img.src = URL.createObjectURL(arquivo); //insere no src da img o caminho da img atual
            img.style.display = "block"; //ativa a exibição da img
            img.style.width = "200px"; 
            img.style.height = "auto"; //esses 2 são tamanho da img

            img.style.marginRight = "10px"; //essa é a margem, a img vai para a direita

            imgpreview.appendChild(img); //appendChild insere a img dentro da div imgpreview, e mostra tudo na tela
        }
    }
            
}

function editarPostagem(id) {
    window.location.href = 'editarPostagem.php?id=' + id;
}

        //function excluirPostagem(id) {
        //    window.location.href = 'excluirPostagem.php?id=' + id;
        //}

function excluirPostagem(id) {
    document.getElementById('confirmBox').style.display = 'flex';
    document.getElementById('confirmBox').dataset.id = id; // Armazena o ID da postagem a ser excluída
}

function confirmar() {
    var id = document.getElementById('confirmBox').dataset.id; // Recupera o ID da postagem a ser excluída
    window.location.href = 'excluirPostagem.php?id=' + id;
}

function cancelar() {
    document.getElementById('confirmBox').style.display = 'none'; // Esconde a caixa de confirmação
}
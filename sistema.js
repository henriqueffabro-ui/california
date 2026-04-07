let upvotes = 0; // Variável para armazenar o número de upvotes, inicializada com o valor do banco de dados
let downvotes = 0;
let votedUp = false;
let votedDown = false;

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
            document.getElementById("comentarios-" + post_id).innerHTML += data.comentario;
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
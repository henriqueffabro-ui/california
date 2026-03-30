function postar() { //ao clicar no botão de postar, a função postar() é chamada
    let titulo = document.getElementById("TituloPost").value; //pega o conteudo colocado no input de titulo
    let descricao = document.getElementById("DescPost").value; //pega o conteudo colocado no input de descrição

    if (titulo === "" || descricao === "") { //se estiverem vaioz, envia alerta falando pra preencher
        alert("Preencha todos os campos antes de postar");
        return;
    }

let divPost = document.createElement("div"); //cria uma nova div para a postagem, onde o titulo e descrição serão inseridos
divPost.className = "post"; //adiciona a classe "post" para a nova div, pra poder colocar css

divPost.innerHTML = ` 
    <h2>${titulo}</h2>
    <p>${descricao}</p>
    <hr>
`; //adiciona o titulo e descrição dentro da div

document.getElementById("posts").appendChild(divPost); 
//adiciona a nova div criada dentro da div "posts", onde todas as postagens ficam
//o appendChild é usado para adicionar um elemento como filho de outro elemento, 
//nesse caso, a nova div criada é adicionada como filha da div "posts"


}// nem isso to usando, tem que apagar


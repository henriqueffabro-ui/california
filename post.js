
      function criarPost() {

    let texto = document.getElementById("postText").value;

    if (texto === "") {
        alert("Escreva algo antes de publicar");
        return;
    }

    let divPost = document.createElement("div");

    divPost.className = "post";

    divPost.innerHTML = `
        <p>${texto}</p>
        <hr>
    `;

    document.getElementById("posts").appendChild(divPost);

    document.getElementById("postText").value = "";

    localStorage.setItem("posts", document.getElementById("posts").innerHTML);

    document.getElementById("postText").value = "";

    window.onload = function () {

    let postsSalvos = localStorage.getItem("posts");

    if (postsSalvos) {
        document.getElementById("posts").innerHTML = postsSalvos;
    }

}
} //isso nao to usando, tem que apagar

 
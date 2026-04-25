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

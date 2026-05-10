<?php
include 'config.php';

function mostrarComentarios($parent_id, $comentarios, $nivel = 0) {

                if (!isset($comentarios[$parent_id])) return; //se nao tiver respostas, não executa o código

                foreach ($comentarios[$parent_id] as $c) { //percorre todos os comentários com tal parent id

                    $espaco = $nivel * -5; //isso calcula a margem adicionada a cada resposta. Quanto mais px, maior a margem

                    

                    echo "<div class='comentario-container' style='margin-left:{$espaco}px'>";

                    echo "<br>";
                    echo "<div class='userinfo'>";
                    echo "<img src='" . $c['foto_perfil'] . "' alt='Foto de perfil' class='pfpimgComent'>";
                    echo "<span class='nome_comentario'>{$c['nome']}</span>";
                    echo "</div>";

                    echo "<div class='comentarioContContainer' id='comentario-{$c['id']}'>";
                    echo "<p class='comentario'>{$c['comentario']}</p>";
                    echo "</div>";

                    echo "<p class='comentario-data'>{$c['data']}</p>";

                // Upvote
                echo "<button class='upvotarcoment' onclick='votarcoment({$c['id']}, 1)'>
                    <img width='15px' src='imgs/arrow.webp'>
                </button>";

                echo "<span id='upvotescoment-{$c['id']}'>" . ($c['upvotes'] ?? 0) . "</span>";

                // Downvote
                echo "<button class='downvotarcoment' onclick='votarcoment({$c['id']}, -1)'>
                    <img width='15px' src='imgs/arrowd.jpg'>
                </button>";

                echo "<span id='downvotescoment-{$c['id']}'>" . ($c['downvotes'] ?? 0) . "</span>";

                echo "<br>";

                // Input respostas
                echo "<input id='resposta-{$c['id']}' placeholder='Responda...'>";
                echo "<button onclick='postarResposta({$c['id']})'>Postar</button>";

                echo "<br>";

                // container respostas
                echo "<div id='Aparecerresposta-{$c['id']}' class='respostas'>";

                // recursão, chama a função novamente para fazer respostas de respostas
                //mostrarComentarios($c['id'], $comentarios, $nivel + 1);

                echo "</div>";

                echo "</div>";
                
            }
        }     

$post_id = $_GET['post_id']; //id do post para o qual queremos carregar os comentários
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0; //offset para paginação, indica quantos comentários já foram carregados

$result = $conexao->query(" SELECT c.comentario, c.data, c.id, c.upvotes, c.downvotes, c.parent_id, u.nome, u.foto_perfil 
                        FROM comentarios c 
                        JOIN usuarios u 
                        ON c.usuario_id = u.id 
                        WHERE c.post_id = {$_GET['post_id']} AND c.parent_id IS NULL
                        ORDER BY c.upvotes DESC, c.id DESC 
                        LIMIT {$offset}"); //limita a exibição a 3 comentários, ordenados pelos mais votados. Os demais comentários podem ser vistos na página do post inteiro
                        
                        if ($result->num_rows > 0) {
                            
                            $comentarios = [];


                            while ($c = $result->fetch_assoc()) {
                                $comentarios[$c['parent_id']][] = $c;
                            }
                            
                             mostrarComentarios(NULL, $comentarios);

                              if ($result->num_rows < 3) {
                                echo "<script>acabouComentarios = true;</script>";
                                
                            }

                             
                            
                            
                        } else {
                            echo "<p>Seja o primeiro a comentar!</p>"; //caso não haja comentários, mostra essa mensagem
                             echo "<script>acabouComentarios = true;</script>";
                        }

                  echo "<div class='qtd-retornada' data-qtd='{$result->num_rows}'></div>";
?>
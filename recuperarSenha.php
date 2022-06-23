<?php
    if (isset($_POST['mailUsuario'])) {
      include "conectaBanco.php";

      $mailUsuario = $_POST['mailUsuario'];


      $sqlUsuario = "SELECT codigoUsuario, nomeUsuario FROM usuarios WHERE mailUsuario=:mailUsuario LIMIT 1";
      
      $sqlUsuarioST = $conexao->prepare($sqlUsuario);  
      
      $sqlUsuarioST->bindValue(':mailUsuario', $mailUsuario);

      $sqlUsuarioST->execute();

      $quantidadeUsuarios = $sqlUsuarioST->rowCount();

      if ($quantidadeUsuarios == 1) {
        $resultadoUsuario = $sqlUsuarioST->fetchALL();

        list($codigousuario, $nomeUsuario) = $resultadoUsuario[0];
        
        $nomeCompletousuario = explode(' ', $nomeUsuario);
        $nomeUsuario = $nomeCompletoUsuario[0];
        
        include "common/gerarSenha.php";

        $novaSenha = gerarSenha(8);
        $novaSenhaMD5 = md5($novaSenha);

        $sqlAlterarSenha = "UPDATE usuarios SET senhaUsuario=:novaSenhaMD5 WHERE codigoUsuario=:codigoUsuario";

        $sqlAlterarSenhaST = $conexao->prepare($sqlAlterarSenha);

        $sqlAlterarSenhaST->bindValue(':novaSenhaMD5', $novaSenhaMD5);
        $sqlAlterarSenhaST->bindValue(':codigoUsuario', $codigoUsuario);

        if ($sqlAlterarSenhaST->execute()){
          // mandar o e-mail
          if ('e-mail enviado'){

          }else {// erro ao gerar a nova senha}
          header("Location: index.php?codMsg=007");
          }
        } else {//erro ao gerar a nova senha
          header("Location: index.php?codMsg=006");
        }
      } else {// usuário não cadastrado
        header("Location: index.php?codMsg=005");
      }
    } else {// e-mail do usuário não informado
      header("Location: index.php?codMsg=004");
    }
?>

<?php

include_once( __DIR__ . '\\client.php' ) ;

if( isset($_GET['action']) )
{

    if( $_GET['action'] == 'list' )
    {
        $mails = mailpitClient::fetchMessages();
        $output = '<div class="list-group">';
        $output .= '<a class="btn btn-secondary my-3 mx-3" style="cursor:pointer" ';
        $output .= "hx-get='./ajax.php?action=list' hx-target='#email-list' hx-swap='innerHTML'";
        $output .= "onclick=ChangeUrl('Mailpit','./')";
        $output .= '>';
        $output .= "Reload Messages";
        $output .= "</a>";
        foreach( $mails['messages'] as $mail ){
            $output .= '<a class="list-group-item" style="cursor:pointer" ';
            $output .= "hx-get='./ajax.php?action=view&id={$mail['ID']}' hx-target='#email-preview' hx-swap='innerHTML' ";
            $output .= "onclick=ChangeUrl('Mailpit','?id={$mail['ID']}')";
            $output .= '>';
            $output .= "{$mail['Subject']}";
            $mailTo = [];
            foreach( $mail['To'] as $mailToAddress){
                $mailTo[] = $mailToAddress['Address'];
            }
            $output .= "{$mail['Subject']}";
            $output .= "<small>";
            $output .= "<br>To: " . implode(", ", $mailTo);
            $output .= "<br>Created: " . $mail['Created'];
            $output .= "</small>";
            $output .= '</a>'; 
        }
        $output .= '<a class="btn btn-danger my-3 mx-3" style="cursor:pointer" onclick="deleteAll()"';
        $output .= '>';
        $output .= "Delete All Messages";
        $output .= "</a>";
        $output .= '</div>';
        print_r( $output );
    }

    elseif( $_GET['action'] == 'view' AND isset($_GET['id']) )
    {
        $mail = mailpitClient::fetchMessage($_GET['id']);
        $output = "<div class='mx-3 mt-5 email-header'>";
        $output .= "<div class='mb-3'>";
        $output .= "<a href='./ajax.php?action=delete&id={$mail['ID']}' class='btn btn-danger'>Delete Message</a>";
        $output .= "</div>";
        $output .= "<strong>From:</strong> {$mail['From']['Name']}  <a href='mailto:{$mail['From']['Address']}'>{$mail['From']['Address']}</a>" ;
        $mailTo = [];
        foreach( $mail['To'] as $mailToAddress){
            $mailTo[] = $mailToAddress['Name'] . " <a href='mailto:{$mailToAddress['Address']}'>" . $mailToAddress['Address'] . "</a>";
        }
        $output .= "<br><strong>To:</strong> " . implode(", ", $mailTo);
        $output .= "<br><strong>Subject:</strong> <span style='font-size:125%;font-weight:600;'>{$mail['Subject']}</span>" ;
        $output .= "<br><strong>Date:</strong> {$mail['Date']}" ;
        $output .= "<br><strong>Tags:</strong> " . implode(", ", $mail['Tags']) ;
        $output .= "</div>";
        $output .= "<hr>";
        $output .= "<div class='mx-3 mt-5 email-body'>";
        $output .= $mail['HTML'];
        $output .= "</div>";
        print_r( $output );
    }

    elseif( $_GET['action'] == 'delete' AND isset($_GET['id']) )
    {
        $mail = mailpitClient::deleteMessage([$_GET['id']]);
        header( "location:./" );
    }

    elseif( $_GET['action'] == 'delete' AND !isset($_GET['id']) )
    {
        $mail = mailpitClient::deleteMessage();
        header( "location:./" );
    }
}
 <title>Generate Captcha Alphabet</title>
    <body style="background-color:#ccc;">

<?php
    create_image();
    display();
    function display()
    {
?>
       <div style="text-align:center;">
            <h3>Captcha Alphabet: </h3>
            <div style="display:block;margin-bottom:20px;margin-top:20px;">
                <img src="/captcha/A.png"> <img src="/captcha/a.png"> </br>
                <img src="/captcha/B.png"> <img src="/captcha/b.png"> </br>
                <img src="/captcha/C.png"> <img src="/captcha/c.png"> </br>
                <img src="/captcha/D.png"> <img src="/captcha/d.png"> </br>
                <img src="/captcha/E.png"> <img src="/captcha/e.png"> </br>
                <img src="/captcha/F.png"> <img src="/captcha/f.png"> </br>
                <img src="/captcha/G.png"> <img src="/captcha/g.png"> </br>
                <img src="/captcha/H.png"> <img src="/captcha/h.png"> </br>
                <img src="/captcha/I.png"> <img src="/captcha/i.png"> </br>
                <img src="/captcha/J.png"> <img src="/captcha/j.png"> </br>
                <img src="/captcha/K.png"> <img src="/captcha/k.png"> </br>
                <img src="/captcha/L.png"> <img src="/captcha/l.png"> </br>
                <img src="/captcha/M.png"> <img src="/captcha/m.png"> </br>
                <img src="/captcha/N.png"> <img src="/captcha/n.png"> </br>
                <img src="/captcha/O.png"> <img src="/captcha/o.png"> </br>
                <img src="/captcha/P.png"> <img src="/captcha/p.png"> </br>
                <img src="/captcha/Q.png"> <img src="/captcha/q.png"> </br>
                <img src="/captcha/R.png"> <img src="/captcha/r.png"> </br>
                <img src="/captcha/S.png"> <img src="/captcha/s.png"> </br>
                <img src="/captcha/T.png"> <img src="/captcha/t.png"> </br>
                <img src="/captcha/U.png"> <img src="/captcha/u.png"> </br>
                <img src="/captcha/V.png"> <img src="/captcha/v.png"> </br>
                <img src="/captcha/W.png"> <img src="/captcha/w.png"> </br>
                <img src="/captcha/X.png"> <img src="/captcha/x.png"> </br>
                <img src="/captcha/Y.png"> <img src="/captcha/y.png"> </br>
                <img src="/captcha/Z.png"> <img src="/captcha/z.png"> </br>
                
            </div>
            
            
        </div>      


<?php
    }

    function  create_image()
    {
        for ($i = 0; $i < 52; $i++)
        {
            $image = imagecreatetruecolor(25, 25); //create the image
            $background_color = imagecolorallocate($image, 255, 255, 255);  
            imagefilledrectangle($image,0,0,200,50,$background_color); 

        // $line_color = imagecolorallocate($image, 64,64,64);
        // $number_of_lines=rand(1,3);

        // for($i=0;$i<$number_of_lines;$i++)
        // {
        //     imageline($image,0,rand()%50,250,rand()%50,$line_color);
        // }

            $pixel = imagecolorallocate($image, 0,0,255);
            for($j=0;$j<10;$j++) //randomly place dots
            {
                imagesetpixel($image,rand()%25,rand()%25,$pixel);
            }  

            $allowed_letters = 'AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz';
            $text_color = imagecolorallocate($image, 0,0,0);
            
            $letter = $allowed_letters[$i];
            imagestring($image, 5,  5, 5, $letter, $text_color);

            //checking if file exsists
            if(file_exists("captcha/".$letter.".png")) //if the file exists, delete & replace
            {
                unlink("captcha/".$letter.".png");
            }
            imagejpeg($image, "captcha/".$letter.".png"); //create the image file
            imagedestroy($image); //destroy the image we created to create a new one
            
        }

       // $_SESSION['captcha_string'] = $word;

    }
?>
    </body>

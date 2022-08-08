<?php
// read in all photos and captions as an array
$pictures = array(
    array(1, "images/cone-and-flowers.jpg", "A waffle cone with caramel drizzle"),
    array(2, "images/friends.PNG", "Three friends wait for their ice cream in our dining room", "Hayley Moniz"),
    array(3, "images/ice-cream-cone.JPG", "One of our employees shows off an exemplary cone","Breanna Morse"),
    array(4, "images/perfect-cone.JPG", "Another exemplary cone"),
    array(5, "images/pumpkin-decorations.jpg", "Our outdoor Thanksgiving decorations")
);

include ("top.php"); ?>

<h2>Gallery</h2>
<p class="intro">Scroll through some photos of our restaurant, including pictures of customers and employees!</p>

<?php

// loop through the array and display each picture and caption in 
// its own figure element
foreach ($pictures as $picture){
    echo '<figure>' .
                '<img src="' . $picture[1] . '" alt = "">'.
                '<figcaption>' . $picture[2] . '</figcaption>' .
         '</figure>';
}

include ("footer.php");
?>

    </body>
</html>
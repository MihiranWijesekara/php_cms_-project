<?php

include("delete_modal.php");

if (isset($_POST['checkBoxArray'])) {
    foreach ($_POST['checkBoxArray'] as $postValueId) {
        $bluk_options = $_POST['bluk_options'];

        switch ($bluk_options) {
            case 'published':
                $query = "UPDATE posts SET post_status = '{$bluk_options}' WHERE post_id = {$postValueId} ";
                $update_to_published_status = mysqli_query($connection, $query);

                break;

            case 'draft':
                $query = "UPDATE posts SET post_status = '{$bluk_options}' WHERE post_id = {$postValueId} ";
                $update_to_draft_status = mysqli_query($connection, $query);

                break;

            case 'delete':
                $query = "DELETE  FROM posts WHERE post_id = {$postValueId} ";
                $update_to_delete_status = mysqli_query($connection, $query);

                break;

            case 'clone';

                $query = "SELECT * FROM posts WHERE post_id = {$postValueId}";
                $select_post_query = mysqli_query($connection, $query);

                while ($row = mysqli_fetch_array($select_post_query)) {
                    $post_title         = $row['post_title'];
                    $post_category_id   = $row['post_category_id'];
                    $post_date          = $row['post_date'];
                    $post_author        = $row['post_author'];
                    $post_status        = $row['post_status'];
                    $post_image         = $row['post_image'];
                    $post_tags          = $row['post_tags'];
                    $post_content       = $row['post_content'];
                    $post_comment_count = $row['post_comment_count'];
                }
                $query = "INSERT INTO posts(post_category_id, post_title, post_author, post_date, post_image, post_content,
            post_tags, post_comment_count, post_status) VALUES($post_category_id, '$post_title', 
            '$post_author',  now(), '$post_image', '$post_content', '$post_tags', 
            $post_comment_count, '$post_status')";
                $copy_query = mysqli_query($connection, $query);

                if (!$copy_query) {
                    die("Query failed " . mysqli_error($connection));
                }
                break;
        }
    }
}
?>



<form action="" method="post">

    <table class="table table-bordered table-hover">

        <div id="bulkOptionsContainer" class="col-xs-4">

            <select class="form-control" name="bluk_options" id="">
                <option value="checkbox">Select Option</option>
                <option value="published">publish</option>
                <option value="draft">Draft</option>
                <option value="delete">Delete</option>
                <option value="clone">Clone</option>
            </select>
        </div>
        <div class="col-xs-4">
            <input type="submit" name="submit" class="byn btn-success" value="Apply">
            <a class="btn btn-primary" href="posts.php?source=add_post"> Add New </a>
        </div>
        <thead>
            <tr>
                <th><input id="selectAllBoxes" type="checkbox"></th>
                <th>Id</th>
                <th>Users</th>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Image</th>
                <th>Tags</th>
                <th>Comments</th>
                <th>Date</th>
                <th>View Post</th>
                <th>Delete</th>
                <th>Edit</th>
                <th>Post Views</th>
            </tr>
        </thead>
        <tbody>

            <?php

            $query = "SELECT * FROM posts  ORDER BY post_id DESC ";
            $select_posts = mysqli_query($connection, $query);

            while ($row = mysqli_fetch_assoc($select_posts)) {

                $post_id = $row['post_id'];
                $post_author = $row['post_author'];
                $post_user = $row['post_user'];
                $post_title = $row['post_title'];
                $post_category_id = $row['post_category_id'];
                $post_status = $row['post_status'];
                $post_image = $row['post_image'];
                $post_tags = $row['post_tags'];
                $post_comment_count = $row['post_comment_count'];
                $post_date = $row['post_date'];
                $post_views_count = $row['post_views_count'];

                echo "<tr>";
            ?>
                <td><input class='checkBoxes' type='checkbox' name='checkBoxArray[]' value='<?php echo $post_id;  ?>'></td>
            <?php

                echo "<td> $post_id </td>";
                
                if( !empty($post_author) ){
                    echo "<td> $post_author </td>";

                } elseif ( !empty($post_user) ){
                    echo "<td> $post_user </td>";

                }

                echo "<td> $post_title </td>";



                $query = "SELECT * FROM categories WHERE cat_id = {$post_category_id}";
                $select_categories_id = mysqli_query($connection, $query);

                while ($row = mysqli_fetch_assoc($select_categories_id)) {
                    $cat_id = $row['cat_id'];
                    $cat_title = $row['cat_title'];



                    echo "<td> {$cat_title} </td>";
                }

                echo "<td> $post_status </td>";
                echo "<td> <image class='img-responsive' src= ' ../images/$post_image' alt='image'> </td>";
                echo "<td> $post_tags </td>";


                // Ensure $post_id is properly set and sanitized
                $post_id = isset($post_id) ? (int)$post_id : 0;

                $query = "SELECT * FROM comments WHERE comment_pos = $post_id";
                $send_comment_query = mysqli_query($connection, $query);

                if ($send_comment_query) {
                    $count_comments = mysqli_num_rows($send_comment_query);

                    if ($count_comments > 0) {
                        $row = mysqli_fetch_array($send_comment_query);
                        $comment_id = $row['comment_id'];
                    } else {
                        $comment_id = 0;
                    }

                    echo "<td><a href='post_comments.php?id=$post_id'>$count_comments</a></td>";
                } else {
                    // Handle query failure
                    echo "Error: " . mysqli_error($connection);
                }



                echo "<td> $post_date </td>";
                echo "<td><a href='../post.php?p_id={$post_id}'> View Posts </a></td> ";
                echo "<td><a rel='$post_id' class='delete_link'> Delete </a></td> ";
              //  echo "<td><a onClick=\"javascript: return confirm('Are You sure you want to delete');\" href='posts.php?delete={$post_id}'> Delete </a></td> ";
                echo "<td><a  href='posts.php?source=edit_post&p_id={$post_id}'> Edit </a></td> ";
                echo "<td><a href='posts.php?reset=$post_id' class='btn btn-success'>$post_views_count</a></td>";


                echo "</tr>";
            }


            ?>


        </tbody>
    </table>
</form>

<?php
if (isset($_GET['delete'])) {
    $the_post_id = $_GET['delete'];
    $query = "DELETE FROM posts WHERE post_id = {$the_post_id}";
    $delete_query = mysqli_query($connection, $query);
    // header("Location: posts.php");
}

if (isset($_GET['reset'])) {

    $the_post_id = escape( $_GET['reset']);
    $query = "UPDATE posts SET post_views_count = 0 WHERE post_id =" . mysqli_real_escape_string($connection, $_GET['reset']) . " ";
    $resetQuery = mysqli_query($connection, $query);

    confirmQuery($resetQuery);

    header("Location: posts.php");
}


?>

<script>
    $(document).ready(function(){
       $(".delete_link").on('click', function(){
         var id = $(this).attr("rel");

         alert(id);

       });
    });
</script
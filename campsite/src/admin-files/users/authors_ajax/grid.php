<?php
ini_set("display_errors","2");
error_reporting(E_ALL);
function l_getType($type)
{
    switch ($type)
    {
        case 1:
            return "Author";
            break;
        case 2:
            return "Photographer";
            break;
        case 3:
            return "Editor";
            break;
       default:
           return "Default";
           break;
    }
}
?>

<table border="0" cellspacing="0" cellpadding="3" class="table_list" id="grid" style="width:100%; margin:0;">
    <thead class="table_list_header">
        <tr>
            <th valign="top">Author</th>
            <th valign="top">Type</th>
            <th valign="top" class="last">Delete</th>
        </tr>
    </thead>
  <tbody>
      <?php
      $authors = Author::GetAuthors();
      $i=0;
    foreach($authors as $author) {
        $class="list_row_even";
        if ($i%2==0)
        {
            $class="list_row_odd";
        }
?>
        <tr class="<?php echo $class ?>" onclick="getRow(<?php echo $author->getId()?>)"  onmouseover="setPointer(this, 0, 'over');" onmouseout="setPointer(this, 0, 'out');">
            <td><?php echo $author->getName();  ?></td>
            <td><?php echo l_getType($author->getType()); ?></td>
            <td align="right" class="last" id="row_0"><a href="?del_id=<?php echo $author->getId() ?>" onclick="return confirm('Are you sure you want to delete this author;')"><img src="../../css/delete.png" border="0" alt="Delete author" title="Delete author" /></a></td>
        </tr>
        <?php
        $i++;

        }?>
  </tbody>
</table>
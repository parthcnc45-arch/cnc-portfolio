<table class="table table-striped table-bordered" id="fav-portfolio-table">
    <thead>
        <tr>
            <th>Company Name</th>
            <th>Website Url</th>
            <th>Country</th>
            <th>Industries</th>
            <th>Framework</th>
            <th>Current Status</th>
            <th>Tags</th>
            <th>Timestamp</th>
            <th>Favorite</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 0;
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $timestamp = strtotime($row['timestamp']);
                $dateformat = date("d/m/Y h:i A", $timestamp);

                $site_preview = $row['site_preview_image'];
                if ($site_preview) {
                    $img = '<img src=' . $site_preview . ' width=50px height="50px">';
                } else {
                    $img = '<img src="http://' . $_SERVER['HTTP_HOST'] . '/cnc_portfolio/images/no-image.png"; width=50px height="50px">';
                }
        ?>
                <tr>
                    <td class="company_name"><?php echo $row['company_name']; ?><span id="company_img_<?php echo $i; ?>" style="display: none;"><?php echo $img; ?></span></td>
                    <td><a target="_blank" href="<?php echo $row['website_url']; ?>" title="Please right click to copy link." class="copy-link"><?php echo $row['website_url']; ?></td>
                    <td><?php echo $row['country']; ?></td>
                    <td><?php echo $row['industries']; ?></td>
                    <td><?php echo $row['framework']; ?></td>
                    <td><?php echo $row['current_status']; ?></td>
                    <td><?php echo $row['tags']; ?></td>
                    <td><?php echo $dateformat; ?></td>
                    <td><i class="<?php echo ($row['favourite'] == '0') ? 'far' : 'fas'; ?> fa-heart toggle-favorite" data-id="<?php echo $row['id']; ?>"></i></td>
                    <td><a href="edit-portfolio.php?id=<?php echo $row['id']; ?>" class="btn mb-1 btn-warning btn-sm" type="button">Edit</a>
                        <button type="button" class="btn mb-1 btn-flat btn-danger btn-sm delete-btn" data-id="<?php echo $row['id']; ?>">Delete</button>
                    </td>
                </tr>
            <?php
                $i++;
            }
        } else {
            ?>
            <td colspan="8" style="text-align:center"><?php echo 'No Data Found'; ?></td>
        <?php
        }
        ?>
    </tbody>
</table>
            <div class="pb-3 ps-3 mt-3 d-flex justify-content-center justify-content-md-between justify-content-lg-between flex-wrap flex-md-nowrap">
                <nav aria-label="Page navigation" class="mb-3 mb-md-0 mb-lg-0">
                    <ul class="pagination" id="pagination">
                        <?php
                        $q = $conn->prepare("SELECT p.p_id, c.category_name, p.name, p.description,
                                        p.stock, p.price, p.image, p.expiry_date, p.status
                                        FROM ep_products p
                                        JOIN ep_category c ON p.c_id = c.c_id
                                        ORDER BY p.price DESC");
                        $q->execute();
                        $count = $q->rowCount();
                        if ($count > 0) {
                            $pages = ceil($count / $limit);  //find total pages of all records per limit

                        ?>
                            <li class="page-item">
                                <?php if ($page > 1) { ?>
                                    <a class="page-link" href="products.php?page=<?= $page - 1 ?>" aria-label="Previous"><i class="fa-solid fa-chevron-left text-size-12"></i></a>
                                <?php } ?>
                            </li>
                            <?php
                            for ($i = 1; $i <= $pages; $i++) {
                                $active = ($i == $page) ? 'active' : '';
                            ?>
                                <li class="page-item"><a class="page-link <?= $active ?>" href="products.php?page=<?= $i ?>"><?php echo $i; ?></a></li>
                            <?php
                            } ?>
                            <li class="page-item">
                                <?php if ($page < $pages) { ?>
                                    <a class="page-link" href="products.php?page=<?= $page + 1 ?>" aria-label="Next"><i class="fa-solid fa-chevron-right text-size-12"></i></a>
                                <?php } ?>
                            </li>

                        <?php } ?>
                        <!-- <li class="page-item active"><a class="page-link" href="#">1</a></li>
                              <li class="page-item"><a class="page-link" href="#">2</a></li>
                              <li class="page-item"><a class="page-link" href="#"><i class="fas fa-ellipsis-h"></i></a></li>
                              <li class="page-item"><a class="page-link" href="#">6</a></li>
                              <li class="page-item"><a class="page-link" href="#">7</a></li>
                              <li class="page-item">
                                <a class="page-link" href="#" aria-label="Next"><i class="fa-solid fa-chevron-right text-size-12"></i></a>
                              </li> -->
                    </ul>
                </nav>
                <!-- <div class="d-flex justify-content-end">
                    <div class="page-selector">
                        <span>PAGE</span>
                        <select class="form-select" aria-label="Select page">
                            <option value="1" selected>1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                        <span>OF 102</span>
                    </div>
                </div> -->
            </div>
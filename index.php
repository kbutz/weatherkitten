<?php
require_once("inc/config.php");
include("inc/header.php");
include("inc/weatherkitten.php");

?>
            
                <form id="locationform" action="inc/validate.php" method="get">
                    <span class="field">Location: </span>
                    <input type="text" name="q" id="q" placeholder="St. Louis" value="<?php echo $q ?>"></input>
                    <select name="units" id="units">
                        <option value="imperial" <?php echo $units == 'imperial' ? 'selected="selected"' : ''; ?>>&#176;F</option>
                        <option value="metric" <?php echo $units == 'metric' ? 'selected="selected"' : ''; ?>>&#176;C</option>
                    </select>
                    <input type="submit" value="Check the weather!"></input>
                </form>
            </div>
            <div class="results">
                <?php 
                    // Check if view-all or a specific image is in the url
                    if (!empty($img)){
                        // Print all images if view-all is selected
                        if ($img == "all") {
                            $all_cats = get_all_cat_imgs();
                            $cat_id = 0;
                            echo "<ul>";
                            
                            foreach ($all_cats as $cat) {
                                /* Display weather code once
                                 * $cat_id_tmp = $cat["weather_code"];
                                 *if ($cat_id != $cat_id_tmp) {
                                 *   echo "<h5>Weather Code: " . $cat["weather_code"] . "</h5>";
                                 *   $cat_id = $cat_id_tmp;
                                 * }
                                 */

                ?>
                                <li>
                                    <a href="?img=<?php echo $cat['id']?>">
                                    <img src='/weatherkitten/img/<?php echo $cat['url']?>' title='Weather code: <?php echo $cat['weather_code'] ?>' class='allcat'/>
                                    </a>
                                </li>
                <?php
                            }
                            echo "</ul>";
                        } 
                        // Check for error or bad image id, load image if found
                        else {
                            $cat = get_single_cat_img($img);
                            if ($cat == false) {
                                echo "<h3 class='alert'>Sorry, I couldn't find that image.</h3>";
                            } else {
                ?>
                            <img src='/weatherkitten/img/<?php echo $cat[0]['url']?>' class='resultcat'/>
                            <p>Cat for weather code: <?php echo $cat[0]['weather_code'] ?></p>
                <?php
                            }
                        }    
                    }
                    else if ($error == "q") {
                          echo "<h3 class='alert'>You must fill in a search term.</h3></script>";
                    } else if (!empty($q)){
                    // get JSON array of locations based on query
                    $location = geocode($q);
                        //geocode returns null if no location matches can be found
                        if ($location == null) {
                            echo "<h3 class='alert'>Sorry, I couldn't find that location.</h3></script>";
                        }
                        else {
                            $weather_data = get_weather($location['latitude'],$location['longitude'],$units);
                            // Checks for imperial vs. metric and prints
                            echo print_city($location);
                            echo print_temperature($weather_data, $units);
                            echo print_weather_description($weather_data);
                            $weather_category = process_weather_category($weather_data);
                            $fetch_cat_array = get_weather_cat_img($weather_category);
                            echo "<a href='?img=". $fetch_cat_array[0]['id'] . "'><img src='/weatherkitten/img/" . $fetch_cat_array[0]['url'] . "' class='resultcat'/></a>";
                        }
                    }
                ?>     
            </div>

<?php include("inc/footer.php"); ?>
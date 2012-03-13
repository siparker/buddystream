<?php
/**
 * Import starter
 */

function BuddystreamFlickrImportStart(){
    $importer = new BuddyStreamFlickrImport();
    return $importer->doImport();
}

/**
 * Flickr Import Class
 */

class BuddyStreamFlickrImport {

    public function doImport() {

        global $bp, $wpdb;
        $itemCounter = 0;
        
            $user_metas = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT user_id
                        FROM $wpdb->usermeta where
                        meta_key='bs_flickr_username'
                        order by meta_value;"
                    )
            );

            if ($user_metas) {
                foreach ($user_metas as $user_meta) {

                   //always start with import = true
                   $import = true;
                    
                   //check for daylimit
                   $max = BuddyStreamFilters::limitReached('flickr', $user_meta->user_id);
                    
                    if ($import && !$max && get_user_meta($user_meta->user_id, 'bs_flickr_username', 1)) {
                   
                        //get the user id
                        $url = 'http://api.flickr.com/services/rest/?method=flickr.urls.lookupuser&api_key='.get_site_option("bs_flickr_api_key").'&url='.urlencode('http://www.flickr.com/photos/'.get_user_meta($user_meta->user_id, 'bs_flickr_username', 1));
                        $response = @simplexml_load_file($url);

                        //get the photos
                        $photosUrl = 'http://api.flickr.com/services/rest/?method=flickr.people.getPublicPhotos&api_key='.get_site_option("bs_flickr_api_key").'&user_id='.$response->user['id']."&extras=date_upload,url_m,url_t,description";
                        $items = @simplexml_load_file($photosUrl);

                         if ($items->photos->photo) {
                                foreach ($items->photos->photo as $item) {

                                   //check daylimit
                                    $max = BuddyStreamFilters::limitReached('flickr', $user_meta->user_id);

                                    $activity_info = bp_activity_get(array('filter' => array('secondary_id' => $item['id']),'show_hidden' => true));
                                    if (!$activity_info['activities'][0]->id && !$max) {

                                        $content = '<a href="'.$item["url_m"].'" class="bs_lightbox" id="'.$item['id'].'" title="'.$item['title'].'"><img src="'.$item["url_t"].'" title="'.$item["title"].'"></a> '.$item["title"]." ".$item["description"];

                                         buddystreamCreateActivity(array(
                                             'user_id'       => $user_meta->user_id,
                                             'extention'     => 'flickr',
                                             'type'          => 'Flickr photo',
                                             'content'       => $content,
                                             'item_id'       => $item['id'],
                                             'raw_date'      => gmdate('Y-m-d H:i:s', (int) $item["dateupload"]),
                                             'actionlink'    => 'http://www.flickr.com/photos/' .$item["owner"]
                                            )
                                         );
                                         $itemCounter++;

                                }    
                            }
                    }
                }
            }       
        }
    //add record to the log
    BuddyStreamLog::log("Flickr imported ".$itemCounter." photo's.");
    
    //return number of items imported
    return $itemCounter;
    }
}

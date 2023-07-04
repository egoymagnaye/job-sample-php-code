/**
* Callback method for adding/updating signage_settings custom field
*
* @since    1.0.0
*/
public function signage_settings_update_field($value, $object, $field_name){

    // Convert the JSON strings into objects
    $data = json_decode($value);

    // Get and sanitize the active media
    $active_media = sanitize_text_field(trim($data->active_media));

    $default_media = ['images', 'vimeo', 'self-hosted'];

    // Check if value is in default media
    if(!in_array($active_media, $default_media, true)){
        $active_media = '';
    }

    
    // Get the images IDs
    $image = $data->images;
    $newImageArr = [];

    // Sanitize image IDs and URLs
    foreach ($image as $key => $item){
        $id = intval(esc_attr(trim($item->id)));
        $url = esc_url_raw(trim($item->url));

        if($id && !empty($url)){
            array_push($newImageArr, ['id' => $id, 'url' => $url]);
        }
    }

    // Get the Vimeo & self hosted URL
    $vimeo = $data->vimeo;
    $self_hosted = $data->self_hosted;

    if (filter_var($vimeo, FILTER_VALIDATE_URL) === FALSE) {
        $vimeo = "";
    }

    if(filter_var($self_hosted, FILTER_VALIDATE_URL) === FALSE){
        $self_hosted = "";
    }

    // Sanitize the Vimeo URL.
    $vimeo = esc_url_raw($vimeo);
    $self_hosted = esc_url_raw($self_hosted);

    $new_data = [
        'active_media'	=> $active_media,
        'images'		=> $newImageArr,
        'vimeo'			=> $vimeo,
        'self_hosted'	=> $self_hosted
    ];
    
    return update_post_meta($object->ID, $field_name, json_encode($new_data)); 
}
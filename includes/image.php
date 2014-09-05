<?php



/**
 * Class image
 *
 *
 *
 * Usage: $banner = new image(array('id' =>$post->ID, 'validate' => 'banner'));
 *
 * id = int(post-id)
 * validate = str(text to find from image
 *
 */
class image {

	public function __construct(array $arguments = array()) {
		global $post;
		$this->id       = ($post ? $post->ID : false);
		$this->validate = false;
		if (!empty($arguments)) {
			foreach ($arguments as $property => $argument) {
				$this->{$property} = $argument;
			}
		}
	}

	/**
	 * Accepts an image id, and returns back all applicable information.
	 * Also accepts secondary paramaters to determine size, and other sizes to get of the same image.
	 *
	 * @param        $thumbID
	 * @param string | array $size
	 * @return bool | array
	 */
	public function get_image($thumbID, $size = 'full') {
		$imageSizes = array();
		if(!is_array($size)){
			$imageSizes[] = $size;
		}else{
			$imageSizes = $size;
		}
		$thumbOBJ = get_post($thumbID);
		if ($thumbOBJ->ID !== null) {

			$imageAttr['id']          = $thumbOBJ->ID;
			$imageAttr['description'] = $thumbOBJ->post_content;
			$imageAttr['caption']     = $thumbOBJ->post_excerpt;
			$imageAttr['title']       = apply_filters('the_title', $thumbOBJ->post_title);
			$imageAttr['alt_text']         = get_post_meta($thumbOBJ->ID, '_wp_attachment_image_alt', true);
			$imageAttr['alt']         = ($imageAttr['alt_text'] == '' ? $imageAttr['title'] : $imageAttr['alt_text']);

			foreach ($imageSizes as $newSize) {
				$imageAttr['image'][$newSize] = wp_get_attachment_image_src($thumbOBJ->ID, $newSize); // returns an array
			}
			return $imageAttr;

		} else {
			return false;
		}
	}

	/**
	 * @param string $size
	 * @param array  $extraSizes
	 * @return array|bool
	 *
	 * Returns the featured image of the post, with the ability to get it in multiple sizes as needed.
	 */
	public function featured_image($size = 'full', $extraSizes = array()) {
		if (has_post_thumbnail($this->id)) {
			$thumbID  = get_post_thumbnail_id($this->id);

			$imageAttr = $this->get_image($thumbID, $size);

			return $imageAttr;
		} else {
			return (bool) false;
		}
	}

	/**
	 * @return bool
	 *
	 * Detects where there is one or more image attached to the page that has the caption $this->validate
	 *
	 * !! DEPRECATED !!
	 */
	public function has_gallery() {
		$falseimg = 0;
		$truimg   = 0;
		$images   = get_posts(array(
			'post_parent'    => $this->id,
			'post_type'      => 'attachment',
			'numberposts'    => -1, // show all
			'post_status'    => null,
			'post_mime_type' => 'image',
			'orderby'        => 'menu_order',
			'order'          => 'ASC',

		));
		if (empty($images)) {

			return false;
		} else {
			foreach ($images as $image) {
				$imgCaption = get_post_meta($image->ID, '_wp_attachment_image_alt', true);
				if ($imgCaption != $this->validate) {
					$falseimg++;
				}
				if ($imgCaption == $this->validate) {
					$truimg++;
				}
			}
			if ($truimg >= 1) {
				return true;
			} elseif ($truimg == 0) {
				return false;
			}
			return true;
		}

	}


	/**
	 * @param string $size
	 * @return array
	 *
	 * Finds all attached images that has caption $this->validate, and returns their information in an array.
	 *
	 * !! DEPRECATED !!
	 */
	public function get_gallery($size = 'full') {
		$return    = array();
		$imageAttr = array();
		$return[$this->id];
		$images = get_posts(array(
			'post_parent'    => $this->id,
			'post_type'      => 'attachment',
			'numberposts'    => -1, // show all
			'post_status'    => null,
			'post_mime_type' => 'image',
			'orderby'        => 'order',
			'order'          => 'ASC',

		));
		if ($images !== '') {
			foreach ($images as $image) {
				$imgCaption = get_post_meta($image->ID, '_wp_attachment_image_alt', true);
				if ($imgCaption == $this->validate) {
					$return[] = $this->get_image($image->ID, $size);
				}
			};
		}
		return $return;
	}


}
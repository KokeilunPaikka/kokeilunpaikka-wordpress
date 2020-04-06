<?php

namespace Sofokus\Plugin\Api;

class Api
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'registerPostEndpoint']);
        add_filter('post_link', [$this, 'changePostUrl'], 999, 2);
        add_filter('page_link', [$this, 'changePostUrl'], 999, 2);
        add_filter('the_permalink', [$this, 'changePostUrl'], 999);
    }

    function changePostUrl($link, $post)
    {
        if (is_int($post)) {
            $post = get_post($post);
        }

        $path = str_replace(get_site_url(), WP_REACT_URL, $link);
        $post_language = pll_get_post_language($post->ID);

        if ($post->post_type == 'page') {
            $path = str_replace('/' .$post_language, '/' . $post_language . '/p', $path);
        } else {
            $path = str_replace('/' . $post_language, '/' . $post_language . '/artikkeli', $path);
        }

        if ($post->post_status == 'draft') {
            if ($post->post_type == 'page') {
                $path = str_replace('p', 'p/new', $path);
            } else {
                $path = str_replace('artikkeli', 'artikkeli/new', $path);
            }
            $path .= '&new=true';
        }

        return $path;
    }

    function registerPostEndpoint()
    {
        register_rest_route('kokeilunpaikka/v1', 'post/(?P<slug>[a-zA-Z0-9-]+)', array(
            'methods' => 'GET',
            'callback' => [$this, 'getPost']
        ));
    }

    function parseBlocks($content)
    {
        $blocks = parse_blocks($content);
        $finalBlocks = [];
        foreach ($blocks as $block) {
            if ($block['blockName']) {
                if ($block['blockName'] == 'acf/hero') {
                    $block['attrs']['data']['image_url'] = wp_get_attachment_image_src($block['attrs']['data']['background_image'], 'full')[0];
                }
                array_push($finalBlocks, $block);
            }
        }

        return $finalBlocks;
    }

    function getPost($request)
    {
        $post_type = $_GET['post_type'];
        if ($post_type != 'post' && $post_type != 'page') {
            exit;
        }
        if (isset($_GET['preview'])) {
            // Preview
            $id = $_GET['preview'];

            // Load the `parent` post first (the regular version of the post)
            $parent_query = new \WP_Query();
            $query_result = $parent_query->query([
                'p' => $id,
                'page' => 1,
                'posts_per_page' => 1,
                'post_status' => ['publish', 'draft'],
                'post_type' => $post_type
            ]);
            $parent = count($query_result) ? reset($query_result) : null;

            // Check for valid post
            if (!$parent || empty($id) || empty($parent->ID)) {
                return new \WP_Error('rest_post_invalid_id', __('Invalid post id.'), array('status' => 404));
            }

            if (isset($_GET['new'])) {
                $post = $parent;
            } else {
                // Now try to load a preview revision
                $revision_query = new \WP_Query();
                $query_result = $revision_query->query([
                    'post_type' => 'revision',
                    'post_status' => 'inherit',
                    'post_parent' => $id,
                    'orderby' => 'post_modified',
                    'order' => 'desc',
                    'page' => 1,
                    'posts_per_page' => 1,
                ]);

                if (empty($query_result)) {
                    return new \WP_Error('empty_category', 'No post found', array('status' => 404));
                }

                $post = $query_result[0];
            }

            $post->blocks = $this->parseBlocks($post->post_content);

            if (isset($_GET['thumbnail'])) {
                $thumbnail = $_GET['thumbnail'];
                $url = wp_get_attachment_image_src($thumbnail, 'thumbnail')[0];
                if ($url) {
                    $post->thumbnail = $url;
                }

                $url = wp_get_attachment_image_src($thumbnail, 'full')[0];
                if ($url) {
                    $post->full_image = $url;
                }
            } elseif (has_post_thumbnail($post)) {
                $post->thumbnail = get_the_post_thumbnail_url($post, 'thumbnail');
                $post->full_image = get_the_post_thumbnail_url($post, 'full');
            }


            $response = new \WP_REST_Response($post);
            $response->set_status(200);
        } else {
            // Actual post
            $args = array(
                'name' => $request['slug'],
                'post_type' => $post_type,
                'posts_per_page' => 1,
            );

            $posts = get_posts($args);
            if (empty($posts)) {
                return new \WP_Error('empty_category', 'No post found', array('status' => 404));
            }

            $post = $posts[0];
            $post->blocks = $this->parseBlocks($post->post_content);

            $url = get_the_post_thumbnail_url($post->ID, 'thumbnail');
            if ($url) {
                $post->thumbnail = $url;
            }

            $url = get_the_post_thumbnail_url($post->ID, 'full');
            if ($url) {
                $post->full_image = $url;
            }

            $response = new \WP_REST_Response($post);
            $response->set_status(200);
        }

        return $response;
    }
}

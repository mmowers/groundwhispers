<?php

class Post extends Eloquent{
	public static $timestamps = true;
	public static $table = 'posts'; //this line really isn't needed because we followed the plural and singular naming conventions.

	public static $rules = array(
		'posttext'=>'required|min:1|max:300',
		'latitude'=>'required|latmatch',
		'longitude'=>'required|lngmatch',
		'uid'=>'required|integer'
	);
	public static function validate($data){
		return Validator::make($data, static::$rules);
	}

	// our post object will belong to an author
	//
	// lets create a belongs_to relationship on the
	// author_id field
	public function author()
	{
		return $this->belongs_to('User', 'uid');
	}

	//the following function is used to access the database to get the list of posts and return them.
	public static function getPosts($user_lat, $user_lng, $sort_by){
		if(!(Earth::latmatch($user_lat)) || !(Earth::lngmatch($user_lng))) {
			return "error";
		}
		//for some unknown reason the live site mysql trips up on the distance_sql_rounded statement,
    //but only in the WHERE clause. WTF?! The local mysql is doing fine with distance_sql_rounded. I am
    //using distance_sql instead of distance_sql_rounded in the WHERE clause below for the live site's sake
    $distance_sql = Earth::earth_distance_sql($user_lat,$user_lng, 'posts');//feet
    $distance_sql_rounded = '(ROUND('.$distance_sql.',-1))'; //rounding to the nearest 10 feet.
		switch ($sort_by) {
    case 'time':
    default:
      $order_by = 'posts.created_at DESC, SUM(votes.value) DESC, '.$distance_sql_rounded.' ASC';
      break;
    case 'distance':
      $order_by = $distance_sql_rounded.' ASC, SUM(votes.value) DESC, posts.created_at DESC';
      //$order_by = $distance_sql.' ASC';
      break;
    case 'votes':
      $order_by = 'SUM(votes.value) DESC, '.$distance_sql_rounded.' ASC, posts.created_at DESC';
  	}
    //for now avoid using fluent and eloquent for complex queries such as this, although this means I must be wary of SQL injection. Also,
    //avoid using the bindings method for query(), because it doesn't allow its placeholder, "?", to be replaced
    //by certain strings, e.g. a column name. Stick to string concatenation and writing my own queries! 
    $distance_limit = 500; //feet
    //$results = DB::query('SELECT * FROM posts WHERE'.$distance_sql.'< '.$distance_limit.' ORDER BY created_at ASC');

    $results = DB::query("SELECT posts.id, posts.posttext, posts.latitude, posts.longitude, posts.created_at, users.username, SUM(votes.value) AS votecount
    	                    FROM posts
    	                    INNER JOIN users ON posts.uid=users.id
    	                    LEFT JOIN votes ON posts.id=votes.pid
                          WHERE $distance_sql < $distance_limit
    	                    GROUP BY posts.id
    	                    ORDER BY $order_by
                          LIMIT 50");
                          //WHERE $distance_sql_rounded < $distance_limit doesn't work on the live site but does work locally. WTF
    for($i=0; $i<count($results); $i++){
      $results[$i]->distance = round(Earth::earth_distance($user_lat, $user_lng, $results[$i]->latitude, $results[$i]->longitude), -1);
      if(empty($results[$i]->username)){
      	$results[$i]->username = 'Anonymous';
      }
      if(empty($results[$i]->votecount)){
      	$results[$i]->votecount = '0';
      }
      //I need to figure out how i want to display the date...
      //if(!empty($results[$i]->created_at)){
      //	//MySQL dates are this format: 'Y-m-d H:i:s'
      //	$seconds_ago = strtotime($results[$i]->created_at) - strtotime(date('Y-m-d H:i:s'));
      //}
    }
    return $results;    
  }
}
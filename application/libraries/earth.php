<?php

class Earth{
	public static $earth_radius_semimajor = 20925600; //ft (6378137.0 meters)
	public static $earth_radius_semiminor = 20855500; //ft (6356752.3 meters)

	// Latitudes in all of U. S.: from -7.2 (American Samoa) to 70.5 (Alaska).
	// Latitudes in continental U. S.: from 24.6 (Florida) to 49.0 (Washington).
	// Average latitude of all U. S. zipcodes: 37.9.
	public static function earth_radius($latitude=37.9) {
	  //global $earth_radius_semimajor, $earth_radius_semiminor;
	  // Estimate the Earth's radius at a given latitude.
	  // Default to an approximate average radius for the United States.

	  $lat = deg2rad($latitude);

	  $x = cos($lat)/static::$earth_radius_semimajor;
	  $y = sin($lat)/static::$earth_radius_semiminor;
	  return 1 / (sqrt($x*$x + $y*$y));
	}

  public static function earth_distance($latitude1, $longitude1, $latitude2, $longitude2) {
    // Estimate the earth-surface distance between two locations.
    $lat1 = deg2rad($latitude1);
    $long1 = deg2rad($longitude1);
    $lat2 = deg2rad($latitude2);
    $long2 = deg2rad($longitude2);
    //MM- I added the following if statement because I was getting NAN instead of 0.
    if($lat1 == $lat2 && $long1 == $long2){
      return 0;
    }
    $radius = static::earth_radius(($latitude1 + $latitude2) / 2);

    $cosangle = cos($lat1)*cos($lat2) *
      (cos($long1)*cos($long2) + sin($long1)*sin($long2)) +
      sin($lat1)*sin($lat2);
    return acos($cosangle) * $radius;
  }
  /*
	 * Returns the SQL fragment needed to add a column called 'distance'
	 * to a query that includes the location table
	 *
	 * @param $latitude    The measurement point
	 * @param $longitude   The measurement point
	 * @param $tbl_alias   If necessary, the alias name of the location table to work from.  Only required when working with named {location} tables
	 */
	public static function earth_distance_sql($latitude, $longitude, $tbl_alias = '') {
	  // Make a SQL expression that estimates the distance to the given location.
	  $lat = deg2rad($latitude);
	  $long = deg2rad($longitude);
	  $radius = static::earth_radius($latitude);

	  // If the table alias is specified, add on the separator.
	  $tbl_alias = empty($tbl_alias) ? $tbl_alias : ($tbl_alias .'.');

	  $coslong = cos($long);
	  $coslat = cos($lat);
	  $sinlong = sin($long);
	  $sinlat = sin($lat);
	  return "(IFNULL(ACOS($coslat*COS(RADIANS({$tbl_alias}latitude))*($coslong*COS(RADIANS({$tbl_alias}longitude)) + $sinlong*SIN(RADIANS({$tbl_alias}longitude))) + $sinlat*SIN(RADIANS({$tbl_alias}latitude))), 0.00000)*$radius)";
	}
  
  public static function latmatch($value){
      return preg_match('#^-?\d{1,2}(\.\d{1,6})?$#', $value);
  }
  public static function lngmatch($value){
      return preg_match('#^-?\d{1,3}(\.\d{1,6})?$#', $value);
  }
}
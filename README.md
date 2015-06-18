# comp412 -hw 2
LUC Summer '15 COMP 412 - Open Source


## Objectives
* Analyze food inspections over the last few years in comparison with Chicago neighborhood boundaries and income levels per neighborhood.  
* Determine if there is a relationship between income & food inspection ratings.  
* Determine best and worst neighborhoods for food inspections.  


## Original Data Sources
* Census Community ID to Postal Zip Code: <http://robparal.blogspot.com/2013/07/chicago-community-area-and-zip-code.html>  
* Food Inspections <https://data.cityofchicago.org/Health-Human-Services/Food-Inspections/4ijn-s7e5>  
* Census Data <https://data.cityofchicago.org/Health-Human-Services/Census-Data-Selected-socioeconomic-indicators-in-C/kn9c-c2s2>  
* (Deprecated) Chicago Neighborhoods by Zip <http://www.dreamtown.com/maps/chicago-zipcode-map.html>  
* (Deprecated) Google Maps with Chicago Neighborhood KML <https://www.google.com/maps/d/u/0/viewer?mid=zYiwUHdW8Wmg.kNxj-e0srss0>  

## Notes about data cleaning
### Food Inspections
Fields used: {Zip, Inspection Date, Results, Latitude, Longitude }  
Records used: June 9, 2015 through Jan 1, 2013  
Results standardized:  
* Fail -> fail
* Out Of Business -> record ignored
* Pass -> pass
* Pass W/ Conditions -> pass
* Not ready -> record ignored
* No Entry -> record ignored
* Business Not Located -> record ignored  
Ignored records and records without a valid zip were removed from data set.

### Census Data
Fields Used: {Community ID, COMMUNITY AREA NAME, PERCENT HOUSEHOLDS BELOW POVERTY, PER CAPITA INCOME }  
Records Used: all records except for aggregated "CHICAGO" record at end of original data set.  

### Census Community ID to Postal Zip Code
Fields Used: {Community ID, Postal Code}


## Known issues with data and processor
* The census data stops at 2010, but records for food inspections are from 2013-2015. We assume the income data can be extrapolated from census data and is unchanged in any significant manor in 5 years.   
* Chicago neighborhood boundaries seem to vary by map. Some maps do not have all the neighboorhoods, some maps (such as the one we are using) have more neighborhoods than the Chicago census data records.  
* Chicago neighborhoods now based on zip code, rather than map (see explanation under section "Changes from original plan").  
* Suburbs such as Cicero are not included in census data and are ignored in food inspection results.  


## Changes from original plan
The original plan was to use Google Maps & KML data to use reverse geocoding on the lat/long to lookup neighborhood. The code for this was developed (see commit #457afb6); however, Google Maps limited to 5 requests per second or 2,500 requests per day. With ~ 45,000 records this was not feasible. Instead, I have taken the approach of using "Community IDs" from the census and mapping these to zip codes, instead of lat/long. The new approach is not quite as perfect as lat/long, but still has high accuracy. Other map sources such as Open Layers were considered but none had the API to support the functionality required.


## Running unit tests
To run the unit tests on your system, you will need to be able to run PHP Unit. Visit <http://phpunit.de> for instructions on setup. Once configured, navigate to the project directory/phpunit and then run "./vendor/bin/phpunit". You may specificy additional flags for additional details. You will also need to edit the file phpunit/lib/elly/ProjectAutoload.php to adjust the PROJECT_ROOT. Lastly, you can view PHP Unit Testing summaries under "phpunit/results".


## Conclusion
Graphically or manually inspecting data shows there is no correlation between Per Capita Income and 
pass rate of food inspections at local restaraunts. Although, again, the data had some issues due to 
map API limits, the overall result is accurate because the data was skewed proportionately.


## Footnotes
All code is heavily commented and follows standard PHP commenting (similar to Java Doc)
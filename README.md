# myPaperdollGenerator
Php Paperdoll Generator For Sphere Server Game Engine


    MyRunUo Php Script -- Sphere Server Entegration -- Rescripted by Avatar

    Link for Php Script ==> http://crowskingdom.bizhat.com/MyRunUO-PHP-1.01/
    Mysql (Sql) file is on web page i linked.

    Example : www.tns.gen.tr/Player_Database/player.php?id=41014

    System operation analogy was taken from RunUo C# script and adjusted for SphereServer

    Requirements : Mysql Server, Apache Server - Php or Remote Connection ( whatever you wish )
    Fundamentals for developing the content : Php Knowledge, Mysql Knowledge

    Just change the myrunuo.inc.php files according to your information such as db username, password, server or so.
    Also, it is important that you should create uofiles folder and put required mul files in it like the following ;

    http://tns.gen.tr/Player_Database/uofiles/

    This is not a full functioning script just a simple example of how to create Paperdoll on Web Page.

    You can adjust it in several ways like player's statistics, online player, events that you created , etc.

    I put one of the example of paperdoll that is functioning in my shard. You can see there.

    Mainly items id is operated in php script according to decimal value. Therefore you should change id value to decimal.

    Like for 0x23443 , make it in script like <eval <0x23443>>. Actually there is no "X" in our sphereserver. For colors, change them decimal too. Like 0234

    ==> <eval <0234>>, so and so forth..
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////


<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class AgentDocumentInsert extends Command
{

    protected $signature = 'AgentDocumentInsert';

    protected $description = 'Command description';

    public function handle()
    {

        /*
        $str = "
        x		truth	fake
        1999	10	マリオネット|||	マリオネット|||
        1999	11	マリオネット|||	マリオネット|||
        1999	12	マリオネット|||	マリオネット|||
        2000	01	マリオネット|||	マリオネット|||
        2000	02	マリオネット|||	マリオネット|||
        2000	03	マリオネット|||	マリオネット|||
        2000	04	マリオネット|||	マリオネット|||
        2000	05	マリオネット|||	マリオネット|||
        2000	06	マリオネット|||	マリオネット|||
        2000	07	マリオネット|||	マリオネット|||
        2000	08	マリオネット|||	マリオネット|||
        2000	09	マリオネット|||	マリオネット|||
        2000	10	マリオネット|||	マリオネット|||
        2000	11	マリオネット|||	マリオネット|||
        2000	12	マリオネット|||	マリオネット|||
        2001	01	マリオネット|||	マリオネット|||
        2001	02	マリオネット|||	マリオネット|||
        2001	03	マリオネット|||	マリオネット|||
        2001	04	マリオネット|||	マリオネット|||
        2001	05	マリオネット|||	マリオネット|||
        2001	06	マリオネット|||	マリオネット|||
        2001	07	マリオネット|||	マリオネット|||
        2001	08	マリオネット|||	マリオネット|||
        2001	09	マリオネット|||	マリオネット|||
        2001	10	マリオネット|||	マリオネット|||
        2001	11	マリオネット|||	マリオネット|||
        2001	12	マリオネット|||	マリオネット|||
        2002	01	マリオネット|||	マリオネット|||
        2002	02	マリオネット|||	マリオネット|||
        2002	03	マリオネット|||	マリオネット|||
        2002	04	マリオネット|||	マリオネット|||
        2002	05	マリオネット|||	マリオネット|||
        2002	06	マリオネット|||	マリオネット|||
        2002	07	マリオネット|||	マリオネット|||
        2002	08	マリオネット|||	マリオネット|||
        2002	09	マリオネット|||	マリオネット|||
        2002	10	マリオネット|||	マリオネット|||
        2002	11	マリオネット|||	マリオネット|||
        2002	12	マリオネット|||	マリオネット|||
        2003	01	マリオネット|||	マリオネット|||
        2003	02	マリオネット|||	マリオネット|||
        2003	03	マリオネット|||	マリオネット|||
        2003	04	マリオネット|||	マリオネット|||
        2003	05	マリオネット|||	マリオネット|||
        2003	06	マリオネット|||	マリオネット|||
        2003	07	マリオネット|||	マリオネット|||
        2003	08	マリオネット|||	マリオネット|||
        2003	09	マリオネット|||	マリオネット|||
        2003	10	マリオネット|||	マリオネット|||
        2003	11	マリオネット|||	マリオネット|||
        2003	12	マリオネット|||	マリオネット|||
        2004	01	マリオネット|||	マリオネット|||
        2004	02	マリオネット|||	マリオネット|||
        2004	03	マリオネット|||	マリオネット|||
        2004	04	マリオネット|||	マリオネット|||
        2004	05	マリオネット|||	マリオネット|||
        2004	06	マリオネット|||	マリオネット|||
        2004	07	マリオネット|||	マリオネット|||
        2004	08	×	マリオネット|||
        2004	09	ドン・キホーテ|||	マリオネット|||
        2004	10	ドン・キホーテ|||	マリオネット|||
        2004	11	×	マリオネット|||
        2004	12	大宮予備校|||	マリオネット|||
        2005	01	×	マリオネット|||
        2005	02	×	マリオネット|||
        2005	03	×	×
        2005	04	×	×
        2005	05	×	×
        2005	06	×	×
        2005	07	×	×
        2005	08	×	×
        2005	09	×	×
        2005	10	×	×
        2005	11	×	×
        2005	12	×	×
        2006	01	×	×
        2006	02	×	×
        2006	03	×	×
        2006	04	アンリミテッド|||	×
        2006	05	アンリミテッド|||	法人回線敷設工事進捗管理|Java||Oracle
        2006	06	アンリミテッド|||	法人回線敷設工事進捗管理|Java||Oracle
        2006	07	×	法人回線敷設工事進捗管理|Java||Oracle
        2006	08	×	法人回線敷設工事進捗管理|Java||Oracle
        2006	09	MEC|||	法人回線敷設工事進捗管理|Java||Oracle
        2006	10	MEC/蒲田|||	法人回線敷設工事進捗管理|Java||Oracle
        2006	11	MEC/蒲田|||	法人回線敷設工事進捗管理|Java||Oracle
        2006	12	MEC/蒲田|||	法人回線敷設工事進捗管理|Java||Oracle
        2007	01	×	法人回線敷設工事進捗管理|Java||Oracle
        2007	02	TIS|||	法人回線敷設工事進捗管理|Java||Oracle
        2007	03	TIS|||	精肉会社通販ポータルサイト|PHP|Zend|PostgreSQL
        2007	04	TIS|||	精肉会社通販ポータルサイト|PHP|Zend|PostgreSQL
        2007	05	TIS|||	精肉会社通販ポータルサイト|PHP|Zend|PostgreSQL
        2007	06	TIS|||	精肉会社通販ポータルサイト|PHP|Zend|PostgreSQL
        2007	07	TIS|||	精肉会社通販ポータルサイト|PHP|Zend|PostgreSQL
        2007	08	×	EC店舗売上管理|PHP|Cake|PostgreSQL
        2007	09	日本技研|||	EC店舗売上管理|PHP|Cake|PostgreSQL
        2007	10	日本技研/TDS|||	EC店舗売上管理|PHP|Cake|PostgreSQL
        2007	11	日本技研/TDS|||	EC店舗売上管理|PHP|Cake|PostgreSQL
        2007	12	日本技研/TDS|||	EC店舗売上管理|PHP|Cake|PostgreSQL
        2008	01	日本技研|||	EC店舗売上管理|PHP|Cake|PostgreSQL
        2008	02	日本技研|||	給食管理|PHP|Cake|MySQL
        2008	03	日本技研/目白|||	給食管理|PHP|Cake|MySQL
        2008	04	日本技研/目白|||	給食管理|PHP|Cake|MySQL
        2008	05	日本技研/目白|||	給食管理|PHP|Cake|MySQL
        2008	06	日本技研/セガロジ|||	給食管理|PHP|Cake|MySQL
        2008	07	日本技研/セガロジ|||	給食管理|PHP|Cake|MySQL
        2008	08	日本技研/セガロジ|||	給食管理|PHP|Cake|MySQL
        2008	09	日本技研/セガロジ|||	音楽学校スクール管理|PHP|Zend|MySQL
        2008	10	日本技研/神田|||	音楽学校スクール管理|PHP|Zend|MySQL
        2008	11	日本技研/神田|||	音楽学校スクール管理|PHP|Zend|MySQL
        2008	12	日本技研/神田|||	音楽学校スクール管理|PHP|Zend|MySQL
        2009	01	日本技研/神田|||	音楽学校スクール管理|PHP|Zend|MySQL
        2009	02	日本技研/神田|||	音楽学校スクール管理|PHP|Zend|MySQL
        2009	03	日本技研|||	営業リスト|PHP|Cake|PostgreSQL
        2009	04	日本技研|||	営業リスト|PHP|Cake|PostgreSQL
        2009	05	日本技研|||	営業リスト|PHP|Cake|PostgreSQL
        2009	06	日本技研|||	営業リスト|PHP|Cake|PostgreSQL
        2009	07	日本技研|||	営業リスト|PHP|Cake|PostgreSQL
        2009	08	日本技研|||	営業リスト|PHP|Cake|PostgreSQL
        2009	09	×	営業リスト|PHP|Cake|PostgreSQL
        2009	10	×	不動産受注管理|PHP|Zend|MySQL
        2009	11	×	不動産受注管理|PHP|Zend|MySQL
        2009	12	バイロン/ABC|||	不動産受注管理|PHP|Zend|MySQL
        2010	01	バイロン/ABC|||	不動産受注管理|PHP|Zend|MySQL
        2010	02	バイロン/ソケット|||	不動産受注管理|PHP|Zend|MySQL
        2010	03	バイロン/恵比寿|||	不動産受注管理|PHP|Zend|MySQL
        2010	04	バイロン/営業リスト|||	×
        2010	05	バイロン/営業リスト|||	セブンエステ|PHP|Symphony|MySQL
        2010	06	バイロン/営業リスト|||	セブンエステ|PHP|Symphony|MySQL
        2010	07	バイロン/営業リスト|||	セブンエステ|PHP|Symphony|MySQL
        2010	08	バイロン/営業リスト|||	セブンエステ|PHP|Symphony|MySQL
        2010	09	バイロン/営業リスト|||	セブンエステ|PHP|Symphony|MySQL
        2010	10	バイロン/トレードセーフ|||	セブンエステ|PHP|Symphony|MySQL
        2010	11	バイロン/トレードセーフ|||	セブンエステ|PHP|Symphony|MySQL
        2010	12	バイロン/トレードセーフ|||	セブンエステ|PHP|Symphony|MySQL
        2011	01	トレードセーフ|||	セブンエステ|PHP|Symphony|MySQL
        2011	02	トレードセーフ|||	セブンエステ|PHP|Symphony|MySQL
        2011	03	トレードセーフ|||	セブンエステ|PHP|Symphony|MySQL
        2011	04	トレードセーフ|||	セブンエステ|PHP|Symphony|MySQL
        2011	05	トレードセーフ|||	セブンエステ|PHP|Symphony|MySQL
        2011	06	トレードセーフ|||	セブンエステ|PHP|Symphony|MySQL
        2011	07	ネクシス/Yahoo|||	セブンエステ|PHP|Symphony|MySQL
        2011	08	ネクシス/Yahoo|||	セブンエステ|PHP|Symphony|MySQL
        2011	09	×	セブンエステ|PHP|Symphony|MySQL
        2011	10	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2011	11	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2011	12	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2012	01	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2012	02	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2012	03	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2012	04	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2012	05	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2012	06	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2012	07	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2012	08	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2012	09	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2012	10	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2012	11	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2012	12	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2013	01	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2013	02	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2013	03	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2013	04	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2013	05	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2013	06	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2013	07	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2013	08	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2013	09	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2013	10	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2013	11	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2013	12	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2014	01	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2014	02	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2014	03	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2014	04	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2014	05	セブンエステ|||	セブンエステ|PHP|Symphony|MySQL
        2014	06	Breast|||	セブンエステ|PHP|Symphony|MySQL
        2014	07	Breast/豊洲|||	セブンエステ|PHP|Symphony|MySQL
        2014	08	Breast|||	セブンエステ|PHP|Symphony|MySQL
        2014	09	×	×
        2014	10	SBC|||	チケット販売ポータルサイト|PHP|Fuel|MySQL、SQLServer
        2014	11	SBC/大門|||	チケット販売ポータルサイト|PHP|Fuel|MySQL、SQLServer
        2014	12	SBC/新宿|||	チケット販売ポータルサイト|PHP|Fuel|MySQL、SQLServer
        2015	01	SBC/新宿|||	チケット販売ポータルサイト|PHP|Fuel|MySQL、SQLServer
        2015	02	SBC/新宿|||	チケット販売ポータルサイト|PHP|Fuel|MySQL、SQLServer
        2015	03	SBC/新宿|||	チケット販売ポータルサイト|PHP|Fuel|MySQL、SQLServer
        2015	04	SBC/求人|||	チケット販売ポータルサイト|PHP|Fuel|MySQL、SQLServer
        2015	05	SBC/求人|||	チケット販売ポータルサイト|PHP|Fuel|MySQL、SQLServer
        2015	06	SBC/求人|||	チケット販売ポータルサイト|PHP|Fuel|MySQL、SQLServer
        2015	07	SBC/求人|||	チケット販売ポータルサイト|PHP|Fuel|MySQL、SQLServer
        2015	08	SBC/ラボ|||	チケット販売ポータルサイト|PHP|Fuel|MySQL、SQLServer
        2015	09	SBC/ラボ|||	求人情報サイト|PHP（5.6）|CakePHP（3.0）|Oracle（11g）、PostgreSQL（8.4）
        2015	10	SBC/蒲田|||	求人情報サイト|PHP（5.6）|CakePHP（3.0）|Oracle（11g）、PostgreSQL（8.4）
        2015	11	SBC/蒲田|||	求人情報サイト|PHP（5.6）|CakePHP（3.0）|Oracle（11g）、PostgreSQL（8.4）
        2015	12	SBC/蒲田|||	求人情報サイト|PHP（5.6）|CakePHP（3.0）|Oracle（11g）、PostgreSQL（8.4）
        2016	01	SBC/蒲田|||	求人情報サイト|PHP（5.6）|CakePHP（3.0）|Oracle（11g）、PostgreSQL（8.4）
        2016	02	SBC/蒲田|||	求人情報サイト|PHP（5.6）|CakePHP（3.0）|Oracle（11g）、PostgreSQL（8.4）
        2016	03	SBC/蒲田|||	求人情報サイト|PHP（5.6）|CakePHP（3.0）|Oracle（11g）、PostgreSQL（8.4）
        2016	04	SBC/蒲田|||	求人情報サイト|PHP（5.6）|CakePHP（3.0）|Oracle（11g）、PostgreSQL（8.4）
        2016	05	SBC/蒲田|||	求人情報サイト|PHP（5.6）|CakePHP（3.0）|Oracle（11g）、PostgreSQL（8.4）
        2016	06	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2016	07	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2016	08	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2016	09	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2016	10	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2016	11	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2016	12	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2017	01	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2017	02	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2017	03	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2017	04	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2017	05	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2017	06	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2017	07	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2017	08	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2017	09	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2017	10	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2017	11	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2017	12	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2018	01	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2018	02	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2018	03	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2018	04	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2018	05	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2018	06	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2018	07	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2018	08	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2018	09	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2018	10	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2018	11	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2018	12	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2019	01	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2019	02	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2019	03	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2019	04	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2019	05	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2019	06	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2019	07	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2019	08	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）
        2019	09	SBC/MVNO|||	SBC/MVNO|PHP（5.6 / 7.2 / 7.3）|Laravel（5.1、5.5、5.8）|MySQL（5.7）

        ";
        */

/*
        $str = "
x		truth	identity	techbiz	olive	an	manoa	threeshake	mid
2019	09	truth	fake	fake	fake	fake	fake	fake	fake
2019	10	ギークス/しまうま|||	×	TDK SharePoint|PowerShell||	TDK SharePoint|PowerShell||	×	×	×	×
2019	11	×	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）	TDK SharePoint|PowerShell||	TDK SharePoint|PowerShell||	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）
2019	12	レバテック/ADHD|||	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）	TDK SharePoint|PowerShell||	TDK SharePoint|PowerShell||	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）
2020	01	レバテック/ADHD|||	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）	TDK SharePoint|PowerShell||	TDK SharePoint|PowerShell||	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）
2020	02	レバテック/ADHD|||	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）	TDK SharePoint|PowerShell||	TDK SharePoint|PowerShell||	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）
2020	03	レバテック/ADHD|||	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）	TDK SharePoint|PowerShell||	TDK SharePoint|PowerShell||	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）
2020	04	レバテック/ADHD|||	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）	TDK SharePoint|PowerShell||	TDK SharePoint|PowerShell||	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）
2020	05	フリエン/森ビル駐車場|||	×	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）	森ビル駐車場|PHP（7.3）|Laravel（7.2）|PostgreSQL（11.12）	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）
2020	06	フリエン/森ビル駐車場|||	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）	森ビル駐車場|PHP（7.3）|Laravel（7.2）|PostgreSQL（11.12）	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）
2020	07	フリエン/無印良品|||	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）	無印良品|PHP（7.4）||MySQL（5.7）	×	×	×
2020	08	フリエン/無印良品|||	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）	無印良品|PHP（7.4）||MySQL（5.7）	カムログ|PHP（7.4）|Laravel（8.5）|MySQL（5.7）	カムログ|PHP（7.4）|Laravel（8.5）|MySQL（5.7）	カムログ|PHP（7.2）|Laravel（6.1）|MySQL（5.7）
2020	09	フリエン/無印良品|||	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）	無印良品|PHP（7.4）||MySQL（5.7）	カムログ|PHP（7.4）|Laravel（8.5）|MySQL（5.7）	カムログ|PHP（7.4）|Laravel（8.5）|MySQL（5.7）	カムログ|PHP（7.2）|Laravel（6.1）|MySQL（5.7）
2020	10	フリエン/カムログ|||	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）	カムログ|PHP（7.4）|Laravel（7.2）|MySQL（5.7）	カムログ|PHP（7.4）|Laravel（8.5）|MySQL（5.7）	カムログ|PHP（7.4）|Laravel（8.5）|MySQL（5.7）	カムログ|PHP（7.2）|Laravel（6.1）|MySQL（5.7）
2020	11	フリエン/カムログ|||	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）	カムログ|PHP（7.4）|Laravel（7.2）|MySQL（5.7）	カムログ|PHP（7.4）|Laravel（8.5）|MySQL（5.7）	カムログ|PHP（7.4）|Laravel（8.5）|MySQL（5.7）	カムログ|PHP（7.2）|Laravel（6.1）|MySQL（5.7）
2020	12	フリエン/バイトル|||	×	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）	バイトル|PHP（7.4）|Laravel（7.2）|MySQL（5.7）	カムログ|PHP（7.4）|Laravel（8.5）|MySQL（5.7）	カムログ|PHP（7.4）|Laravel（8.5）|MySQL（5.7）	カムログ|PHP（7.2）|Laravel（6.1）|MySQL（5.7）
2021	01	フリエン/バイトル|||	TDKラーニング|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）	バイトル|PHP（7.4）|Laravel（7.2）|MySQL（5.7）	カムログ|PHP（7.4）|Laravel（8.5）|MySQL（5.7）	カムログ|PHP（7.4）|Laravel（8.5）|MySQL（5.7）	カムログ|PHP（7.2）|Laravel（6.1）|MySQL（5.7）
2021	02	ジェニュイン/TDK SharePoint|||	TDKラーニング|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	ADHD対|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	カムログ|PHP（7.2）|Laravel（6.1）|MySQL（5.7）
2021	03	ジェニュイン/TDK SharePoint|||	TDKラーニング|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	ADHD対|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）
2021	04	ジェニュイン/TDK SharePoint|||	TDKラーニング|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	ADHD対|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）
2021	05	ジェニュイン/TDK SharePoint|||	TDKラーニング|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	ADHD対|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）
2021	06	ジェニュイン/TDK SharePoint|||	TDKラーニング|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	ADHD対|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）
2021	07	ジェニュイン/TDK SharePoint|||	TDKラーニング|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	ADHD対|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）
2021	08	ジェニュイン/TDK SharePoint|||	TDKラーニング|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	ADHD対|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）
2021	09	ジェニュイン/TDK SharePoint|||	TDKラーニング|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	ADHD対|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）
2021	10	×	TDKラーニング|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	ADHD対|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）
2021	11	フォスター/epark|||	TDKラーニング|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	ADHD対|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）
2021	12	フォスター/epark|||	TDKラーニング|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	ADHD対|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）

";
*/



/*
        $str = "
x		h-basis
2019	09	fake
2019	10	TDK SharePoint|PowerShell||
2019	11	TDK SharePoint|PowerShell||
2019	12	TDK SharePoint|PowerShell||
2020	01	TDK SharePoint|PowerShell||
2020	02	TDK SharePoint|PowerShell||
2020	03	TDK SharePoint|PowerShell||
2020	04	TDK SharePoint|PowerShell||
2020	05	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）
2020	06	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）
2020	07	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）
2020	08	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）
2020	09	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）
2020	10	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）
2020	11	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）
2020	12	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）
2021	01	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）
2021	02	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）
2021	03	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）
2021	04	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）
2021	05	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）
2021	06	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）
2021	07	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）
2021	08	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）
2021	09	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）
2021	10	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）
2021	11	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）
2021	12	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）

        ";
*/


/*
$str = "
x		commitgrowth
2019	09	fake
2019	10	×
2019	11	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）
2019	12	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）
2020	01	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）
2020	02	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）
2020	03	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）
2020	04	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）
2020	05	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）
2020	06	ADHD|PHP（7.3）|Laravel（5.5）|Oracle（11g）
2020	07	×
2020	08	カムログ|PHP（7.4）|Laravel（8.5）|MySQL（5.7）
2020	09	カムログ|PHP（7.4）|Laravel（8.5）|MySQL（5.7）
2020	10	カムログ|PHP（7.4）|Laravel（8.5）|MySQL（5.7）
2020	11	カムログ|PHP（7.4）|Laravel（8.5）|MySQL（5.7）
2020	12	カムログ|PHP（7.4）|Laravel（8.5）|MySQL（5.7）
2021	01	カムログ|PHP（7.4）|Laravel（8.5）|MySQL（5.7）
2021	02	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）
2021	03	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）
2021	04	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）
2021	05	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）
2021	06	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）
2021	07	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）
2021	08	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）
2021	09	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）
2021	10	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）
2021	11	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）
2021	12	TDKラーニング|PHP（7.4）、Dart（2.6）|Laravel（7.2）、Flutter（1.17）|MySQL（8）

";
*/



        $str = "
x		potepan
2019	09	fake
2019	10	TDK SharePoint|PowerShell||
2019	11	TDK SharePoint|PowerShell||
2019	12	TDK SharePoint|PowerShell||
2020	01	TDK SharePoint|PowerShell||
2020	02	TDK SharePoint|PowerShell||
2020	03	TDK SharePoint|PowerShell||
2020	04	TDK SharePoint|PowerShell||
2020	05	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）
2020	06	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）
2020	07	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）
2020	08	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）
2020	09	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）
2020	10	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）
2020	11	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）
2020	12	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）
2021	01	カムログ|PHP（7.2）、Dart（2.6）|Laravel（6.4）、Flutter（1.15）|MySQL（5.7）
2021	02	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）
2021	03	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）
2021	04	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）
2021	05	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）
2021	06	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）
2021	07	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）
2021	08	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）
2021	09	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）
2021	10	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）
2021	11	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）
2021	12	ADHD|PHP（7.4）、Dart（2.8）|Laravel（8.5）、Flutter（1.17）|MySQL（5.7）

        ";















        $ex_str = explode("\n", $str);

        foreach ($ex_str as $k => $v) {
            if (trim($v) == "") {
                continue;
            }

            $ex_v = explode("\t", trim($v));

            $year = "";
            $month = "";
            $ary = [];
            if ($ex_v[0] == "x") {
                $agent = $ex_v;
            } else {
                $i = 0;
                foreach ($ex_v as $k2 => $v2) {
                    switch ($k2) {
                        case 0:
                            $year = trim($v2);
                            break;
                        case 1:
                            $month = trim($v2);
                            break;
                        default:
                            $ary[$i]['agent'] = trim($agent[$k2]);
                            $ary[$i]['content'] = trim($v2);
                            $i++;
                            break;
                    }
                }
            }

            $insert = [];
            foreach ($ary as $k3 => $v3) {
                $insert[$k3]['year'] = $year;
                $insert[$k3]['month'] = $month;
                $insert[$k3]['agent'] = $v3['agent'];
                $insert[$k3]['content'] = $v3['content'];
            }

            if (empty($insert)) {
                continue;
            }

            print_r($insert);

//            DB::table('t_agent_document_a')->insert($insert);
            DB::table('t_agent_document_b')->insert($insert);
        }
    }
}

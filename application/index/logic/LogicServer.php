<?php
namespace app\index\logic;
use think\facade\Cache;
use think\facade\Log;

// require("cmd.php");
// require("pack.php");

use app\index\model\User;
use app\index\logic\LogicCode;

class LogicServer
{
	function __construct($name) {
        $this->name=$name;

    }
    
    function test($arr)
    {
        $arr['name'] = 2;
    }

	function process()
    {
    	if (!request()->has('params','get')){
    		return Pack::output_fail(LogicCode::ERR_PARAMS);
    	}

        $params = request()->param('params');
        $params = json_decode($params, true);
        
        if (null === $params){
        	return Pack::output_fail(LogicCode::ERR_PARAMS); 
        }

		$action = $params['action'];

// call_user_func(__NAMESPACE__.'\incrementa', $a);
		$bexist = method_exists($this, $action);
		if (!$bexist)
		{
			return Pack::output_fail(LogicCode::ERR_PARAMS);
		}
		return call_user_func_array(array($this, $action), array($params));
    }

    function login($params)
    {
    	// return LogicCmd::ReqLogin;
    	// return LogicCode::Desc(1000);
    	$cmd = isset($params['cmd']) ? $params['cmd'] : 0;
    	$sid = isset($params['sid']) ? $params['sid'] : 0;
    	$uid = (int)Cache::handler()->get(utils::SID($sid));
    	do{
			if (!$uid)
	        {
	        	if ($cmd){
	        		Log::error("login not find user");
	        		break;
	        	}

                $arr_nickname = ["唠甜嗑","时常饿","唠甜嗑","半衾梦","子栖","允世","阪姬","極樂鬼","甜是你","春夜浅","满栀","思檀郎","惟欲睡","花辞树","星軌x","离鸿","書生途","舟遥客","暗中人","遮云壑","紅太極","枕花眠","云巢","盗琴音","赠佳期","佞臣","战皆罪","献世佛","三生路","忘羡","东风软","纵山崖","孤凫","思慕","贪了杯","风月客","探春","远山浅","杯别","送舟行","枫以","缘字诀","甜中书","鸠书","山有枢","ぇ气","狱中猫","玩世","南烟","弥枳","生生漫","行雁书","桃扇骨","野稚","赠意","山川志","秋桜","霊感","清旖","风铃鹿","忱杏","烟酉","西岸风","罪歌","椒妓","兔姬","酒几许","煮诗","摆渡翁","野途","橘淮北","逐風","几欢","世俗姬","平野宿","梓桑","一秋","咦哩","呆喜","猫景","恕己","鱼忧","文饰","予囚","佘樂","冬灼","辜予","早乙女","游魂喵","执傲","神择","煞尾","薄情册","猎心人","紹介に","薄情眉","別れ","野慌","辄乜","幕倦","怙棘","駦屿","走野","七凉","二奴","鱼是乎","隔川盼","睬姥","夙月","寰鸾","渊鱼","素歆","粉霞","雾月","十鸦","青尢","嘤咛","雾夕","逃夭","似蜜餹","樱甜","渡鹤影","山柰","山柰","胭話","撞了怀","淤浪","鱼芗","烟柳","小青鲤","扶尾猫","罪鹜","绣羽","等灯","吻笑眉","枝桠","归鹤鸣","轻佻酒","寻欢客","饮舟","枕山忧","鸽屿","野鹿子","闻枯","疚爱","将离","丑脸谱","拾荒鲤","春慵","云棉","千夜","十驹","平生事","叹沉浮","劣戏","迟山","旧脸谱","过活","北念","眉薄","拔弦","稚鬼","软妹串","喵叽","雨安","卿绡","颇倔","卿初嫁","相思劫","谨兮","方且","慌归。","诤友","涴歌","折青杏","简妗","甜吻","梦奴","杳鸢","扶弦","孚鲸","三秋远","觅遇","浊厌","优伶","辞忧","败骨","木白","七禾页","酒颂","冷脾气","卿忬","轻禾","折木","病态棘","卮留","寻倌","早川","闻呓","汐鸠","远镇","北渚","饮惑","以酷","近箐","聊慰","吲‖鸣","朮生","南斋时","白野駒","晌融","貪欢","倥絔","够运-","禾厶 谷欠","鱼窥荷","南戈","囍笑","笑忘罢","酒废","路弥","°懵少女","花想c","酒中人","帅冕","网白","鸠魁","腻橙味","逐鹿","辞取","绮烟","月棠","桜花祭","千仐","同尘","两仪","云柯","浴红衣","池木","塔塔猫","九级浪~","羞稚","花桑","南七夏","谷夏","奢欲","又怨","笙痞","凉墨","帝王念","温折酒","七禾","绅刃","慵挽","纵性","萌辣","比忠","醉南桥","薄荷港","谜兔","蒗幽","溇涏","南简","皆叹","迷麇","西奺","邶谌","芩酌","婼粥","顾奈η","鹿达令","杞胭","粢醍","一镜","俛就","青冢暮","寄晴","嗜橘歌","拥欲","树雾","几渡","晚鲸","择以年","葵袖","九龄","偏执美","莘夏","長野","秙暔","夏棠","幻想客","风晓","清晓","温柔词","执伞者","橙柒","-萌叽-","雨铃","而川","痞唇","劫外身","戏侃","怯朲","钟晚","傲娇狗尾艸一支","故侍","离祭","桃靥","桃花扇r.","莺时","扮乖","执刀手","喵木er","照雨","僚兮","奚落","猫爷","婉绾","浪胚","柚笑","秋风行","摘风","朻安","听净","近臾","入云栖","寂星","倦话","惘说","炽春","美男兮","拥醉","萌ing","木緿","未芩","节枝","弥繁","云胡","嗫嚅","池予","安娴","茶花眉","唔猫","歆笙","岁吢","暗喜","佼人","怼怹恏","野侃","绾痞","栖迟","海夕","南续","云裳","木落","清眉祭","挽清梦","久隐师","岛徒","心里鬼","半枫","稀香","怀桔","白况","缪败","中二柚","叹倦","颜无敌","哽咽笑","清引","梦冥","時窥","素罗衫","眸中客","冧九","绮筵","鹿童谣","哀由","瑶笙","风渺","十雾","饮湿","风蛊","风流物","艳鬼","未欢","橘寄","五里雾","乜一","柠木","欲奴","揽月","晚雾","并安","千紇","述情","绿脊","拥野","挽鹿","尢婠","AD桔","做啡","囍神","长安奴","几钵","嵶邸","痴魂","二囍","纯乏","戏骨浪","纵情罪","酒卿女","野浓","智障猫","矫纵","笑羞容","眉妩","鹿凉人","野梦","沐白","叙詓","卬妄","鹊惊枝","勒言","漠望","探情意","栀意","珞棠","怯慌","寄认","冂马","悸初","池北鱼","两面生","寺瞳","麓屿","梦息","零栀","妄愿","情票","俗欲","迟醉","软祣","无尾风","棕眸","軟酷","旧顾戈","俗了风","箴词","岁笙","喜余","路岷","氿雾","眼戏","娉如画","笑惜","薄喜","倾弋","织谜","酷腻","娇喘妓","鹿鸢","任谁","辜屿","友欢","债姬","山人契","私野","望喜","方觉久","吝吻","我俗","羁客","尤怨","过渡客","厌味","铃予","夏见","锦欢","君勿笑","王囚","择沓","原野","戈亓","入怼.","旧竹","空宴","欢烬","千鲤","池虞","信愁","热耳","隐诗","离旧人。","不矜","澉约","野の","人生戏","猫卆","征棹","寄人书","邮友","忆沫","傻梦","独语","欲拥i","眼趣","望笑","作妖","昨迟人","千秋岁","里赴","七婞","眼藏柔","会傲","好倦","橪书","空名","青朷","美人骨","橘亓","辞慾","稚然","各空","撩人痒","殊姿","昇り龍","榆西","空枝","稻﹋城","音梦","情满目","清妩","西风误","话孤","苍阶","里予","倾酏","媚醉意","遐迩","秋酿","秋南枝","妏与","婳悕","猫杦杦","渔阳","乙白","槿畔","宠臣","听茶","抹忆","卮酒","池鱼","墓栀","旧谈","初霁","辞别","念稚","空山梦","心児","南忆","扰梦","旧我","偶亦","帝王局","奶味刀","私房礼","沫栀°","诗呓","怎言笑","橘欢","雾敛","娇痞","徒掠","昭浅","萌晴","城鱼","叔途","随浪潮","懵圈儿","拒梦","晕白","宠臣","鸢栀","午言","雾心人","羡兔","命轴","书尽","秀不羁","释欢","情授","本萝","野酷士","玖橘","听疏雨","好怪","酒岁","_琴夏","何拆","V_V","瞳韵i","伊人诀","孤望","书中人","愚季","浪妓心","謓念","饮酎","擅傲","折奉","痴子","乘鸾","未几","酌锦","羁拥","瘾然","挽吟袖","尝蛊","亡鸦","嘟醉","桔烟","蔚落","常安","蝉声远","归于瘾","清淮","抌妤","哑萝","掩灼","袖间","橘子巷","寒洲","猫咚","酒事","偃师","別咬唇","忆囚","庸颜","南山下","咽渡","顾执","醉清弦","孤心匠","怎忘","余安","性许","莣萳","柔侣","只影","弦久","烟中客","心内言","孤鱼","泪灼","温人","痴者","澄萌","世味","夙世","冢渊","清秋愁","俗野","森槿","离鸢","痴妓","鸠骨","语酌","妄言者","美咩","酒奴","冬马","末屿","萌懂","南殷","囤梦","尽心独","纵遇","蓝殇","鹿岛","掩吻","双笙","野欢","拥嬉","可难","假欢","辙弃","笙沉","丑味","寻欢人","馥妴","逢旧人","伞边猫","闹旅","听弧","礼忱","只酷","撩艳鬼","惑心","冷热情","嘻友","晴枙","颜於","竹祭","辞眸","孤央","青迟","萝莉病","怀拥你","瑰颈","依疚",";年⒊岁","痛言","七寻笑","鸢旧","慵吋","鸽吻","北槐","寻妄","边侣","长绊途","绿邪","舔夺","忿咬","断渊","时洐","空盏","匿名街バ","衬旅,","意味","柚屿","早嗔","异﹍体﹉","稚欲","冘醉","残弦","别掩","寻暖","假性子","顾谁绪","及巳","素素猫","琯眉","···狂浪···","卷耳","阑森","苍麓","森柠i","策马路","意迟","初倾","三秒拥","森瑾猫","良泯","旧忌","千笙结","简柠","云终韵","橘眸","[圶遇]","凭妒","献世礼","撩瘾","禾莞l","续梦","o鹿遇o","狼斩","皆非","赴野","悲妓","怂欲","欲味","路半","蛮缠","尸惑","夭女","离忧","兮徒","娰麋","顾言友","浮说","瘾柔","季箐",":骨吻","凛蝶","嗜心","熟友","稚浪","喃语","情胃","归遇","高冷怪","酒窖女","少话妖","贝兎","轻叙","厌遇*","旅芷","绿囹","吻温","温煦","自由病","余瘾","硬磕╱°","终陌","奇谲","变脸狐","颜君","怣狂","长乐岁","择终","白马囚","画榆人","酥吟","屿风","鸣筝","日光瘾","知期","萌麋","瞳海","心靓","夏蝉°","时澄","邶风","迎呓","墓北","夏鲤○","身控","桔匣","以喃°","温戾","啐喵","魔法污","肆心","厝柩","美呙","拥散","喜忧女","眠医","瘾孤","权痞","尕柚","撩梦","多情癖","害你笑","归栀","尾晴","阔野","崆野","善茬","黒欲/","矫情疯","暮寒","赔伴","冰枷","橘暖","顽活","笙棯","曳女","稚幼","毒刊","夏栖","低城","难勉","耍弄","花岁","云野","贪得","红焚","写荣","允赴","亡影","躲目","枳屿","祈欢","碰指","浪九","尤礼","妄比","体烫","奶猫","怪人闲","纯吻涩","浪战","船渡人","佳音","酿猫","萌眯","鹿君","浪世","奢情","晴鸢","伪誓","渐笛","谷欠","怪痞","择唇","茶笙","怎尽","野劲","匁杀","眉宇嗔","山尚","盐厌","花心症","瞳荧","南梵","余歆","初橙","离梦","苦妄","酸鸦","听目","久夏青","栀眠","毒饮","安柠","逢时","初欢","怎敢扰","嚼你","单雨徒","氛圍","逗崩","贪心瘾","涙笑","懵鸟","套路脏","笑昧","独照","清疚","俗染","挽卿歌","獵謊師","鹿橘","1人欲","浮森","骄冽","記柔刀","泊舟","纵横家","萌吟","忍冬","柠初","糜废","素囧","泠渊","逐野","夏筝","扶袖","尘音","效恪","清城刊","青晓","时迁","南离","几故","念碎","斯人","不二臣","橘町","惯劣","帝王妹","几甽","笑厌","风物","尤友","桃安","酒浪","皱山眉","莓森","网磕儿","擒梦师","话旧","伪笑","废途","余厌","疚过","俗坯","拥失","南頔","任然","荤话","玩物痴","浪喘","侥幸者","反话","放荡症","暗吟","单野。","清喜","潇宿","泛滥心","森雾","浪栀","鹤冢","吾友","污聊","性空山","丝萝","戰<野","白雪姬","妄征","愚剧","顾慌","花嫁","懵圈°","浪婳","脸容","樱寻","心下囚","啼笑","姓他","东嗜","十七夏","橘梦","早阳","囚念","清决","流浪癖","衫痞","元气鹿","青眯","孤戾","透心","帽客","往森","乏味","十罪","烟久","瘟疑","疯痞","手刃","罕女","饥荒鬼","饶心","神经暖","色娘","却似风","独羁","婪隐","绅女","孤猫","旧阳","帅女","酒绊","七绪","扶渊","茶色酒","独路","玩友","拘礼","撑伞人","盛席","野酒","怯梦","戒浪","伴妓","喵的","调妓","奢弥","重燃","八巷","鹿篱","婳峪","情话婊","故朽","孤澈","套路","宿栀","嗜欲","情绕","浮影","空船","花鹿","施妄者","几人甘","独栖","孑城","森迟","性子脏","简笙","几曾有","诗禅","丑态","猜笑","离念","话寡","久已忘","轻鹄","烟嗓","橘又青","脖间痣","颦笑","盲笑","栖世","吻荒。","未衷","长辞","旧寂","久故","又夏","俊人","柒喵","惘友","草莓味","浪鸡","老街头","话唠","沙话","情裂","你好野","或缺","野子","倚肩","挽喜","和风","百囚","孤鸣","玩乐","像热烈","绅士忌","⊙无脸怪","勾欢","宗鹿","清秀","暖森","2暖","开森啊","污师","情如风","遥途:","熬情","诱食熟","老梗","颤吻","橙诱","空气吻","独言","清梦","亡忆","殊遇","故交","屎比比","死宅","痴人愿","怎挽","囍旧","终憾","谩赢","书札","夜朝","诗色","青遥","欢脱","风快","路友","谷风","半边树","热忘","独久","呆望","南烛","渣情","隐疾","沧浪","傲戾","空拥","萌猛","野客","悔杀","离葵","简白","笑冕","还吻","寒裳","轻梳","樱笑","绳情","乜嘢","短叹","怣人","明谎","嘲愚","愁杀","瘾病","残杯","轻叩","烟云中","恶意","弯儿月","浪姬","祝颂","软酱","携袂","似瘾","污婆","嗜笑","欲感","展眉","撩火","描摹","鲸落","浪游","相妒","艰涩","诱犯","堪笑","雾缭","烟疤","余悸","宠命","朝拾","蜜语","热欲","爱癌","吹愁","细鬼","吃醉","共喰","御守","镜丑","余囚","自赎","冷枪","氐惆","戒瘾","娼妓","阿笑","猫馆","岁晚","仙隐","痞友","造情","喥鸢","萌奶","山雨","执妄","山人","青桔","素骨","尾夏","妄碍","栖梧","风依","窥欲","欠嘴","陋俗","枝绿","温差","战妓","娶酒","戏厌","婉肆","辞画","梵行","活脱","浅寐","离景","红引","棾侣","孤馆","狼决","淤人","欠女","冗词","浪仔","樱九","亢潮","乘鲤","岁情","吻骨","熟稔","脉纹","舌味","眼窥","流萤","拙劣","娇矜","假寐","青瓦","七苦","娇花","寂笙","捕风","枯颜","稚子","雾散","恨醉","痞气","朕卿","信情","帅跪","烬余","敢试","巧逗","有瘾","痴怨","情烫","低唤","载尘","俗酿","悲秋","囚瞳","调笑","酒痕","情言","情知","逐你","浪纯","蜚语","荒弥","明了","愿见","跋涉","夜喃","悟我","敛歌","像烟","独纵","猫饵","鹿予","游书","野居","疯浪","上邪","蒙春","鹿眸","稚性女","女呋","醉沓","酉井","嬲噻","予笙","情契","承愚","述禾","冗杂","羁鸟","寐亓","渡情","病疚","憾事","鹿瘾","青词","巷末","从心","醉者","故梦","街拥","欢愉","网妓","浮炔","虞芮","賣笑人","浪冢","酒肆","边荒","森囚","作呕","怨情","厌心","奴庸","尽野","绾妓","苍颜","晚灯","哪堪","烫喉","远妄","止宠","钟情","灼笑","花客","孤战","子歇","初捻","正恨","杯尽","几幺","汝久","九仄","共谁","夜寒","妓奴","浪骨","千古","解愁","数尽","久往","自对","热呛","青雀","偏幽","轻诉","最怜","尽放","剑笙","且空","旧郎","九槑","孤稚","祭颜","冢话","扮野","宛梦","浓墨","且醉","倦寝","清坟","拨弦","像戏","贪向","初恋","暖医","九酌","执刀","挽与","醉妓","愚勇","有鹿","杂匿","魔障","旧诱","眠云","凭你","傲影","浅酌","结疤","袭人","不染","南吻","被酒","浪人","烟楼","≒性瘾","鬼眨","顾温","予情","鹿稚","嫁风","但愿","情皱","倦忌","棘之","通病","野欲","暗痛","失傲","两生歡","共饮","心怠","袖拥","沉渣","蚀初","烛半","云诀","困破","朽恐","另忆","暖玺","叠花","演员","绾袖","权杀","左音","舔嗜","孤你","枯萌","且尽","猫骨","已笙","善酒","巷欲","葵夻","夕蝶","堕梦","孤匠","歪念","时疚","相许","窗枳","麻饭","浪客","嗔怪","裸橙","木岛","与鲤","南途","清悸","橙弋","作哑","心苦","森鹿","橘年","口勿","无忧","清颜","泯怜","雾漫","箜澈","孤檠","难觅","几念","柚友","飘渺","祀城","共鱼","爱喜","琴淙","送酒","归程","雪颜","碎嘴","浪Θ阿浪","难笑","ㄊが亻","返航","玖染","怨兮","影怯","软笙","烫心","风涟","逐··浪","心抖","不归徒","橘殃","未信","蛇信","微风","忠妾","話柠","凉初","沥鹿","冷柠","口喜","漫步","偏激","冷城","温衫","空欢","诉初","拧眉","悍纸","当真","波浪","栀曦","走偏","树句","没有调","祭·爱","隐世","饮啄","忱息","意折","挽笙","泪枯","嗲气","眼热","几叶","掌纹","南璃","寄已","朽鹿","雾灯","南温","相思烈","负势","青怏","余决","厌战","凉屺","废情","何寐","微蹙","与共","孤韧","情徒","可原","才几","千疚","不暖","秋水醉","旧病","犹碧","泪意","冷痴","花败","娿孬","诗音","再兑","稚笙","仄情","晚空","清恬","心傲","吟留","傻话","细痒","垂眸","久愁","七等","向挽","一半","更酌","路遇","痞傲","森花","带种","趁女","稚狂","烟栀","笑脸妹","旧耳","清泪","魇腐","喘息","劣女纪","情舌","清诉","楠逃","重轻","过骗","忘过","嗔痴","滚喉","寻朲","橘未橙","支鸟","浪旅","森野","庸抱","扯清","声声","別話","怯朲","够野","森 海","伪弋","南石","庸语","南徊","卷猫","允你","甚怜","喵酱","酒久","荒城","淘汰者","澐染","拐众","言晚","走枪","敢吻","怂猫儿","舌瘾","梦姬","耳熟","骨歌","故久","叵测","蒺藜","胡话","亡己","浪笑","余浪","南屿","杯子君","努你","伤泪"];
                $rand_idx = rand(0, count($arr_nickname)-1);
	        	$user           = new User;

				$user->nickname     = $arr_nickname[$rand_idx];

                $user->save();

				$uid = (int)$user->id;
				$sid = utils::GUID();

// urlencode("http://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83eoiaTFQcOicxnTsjibvD5KCOzQs8tV3DNa6tuEnJCjgZC0IhmPj0jxARZBaYbqH5n8TnbLzO40D1A2YA/96")
                $avatar = ("http://localhost:8888/static/images/random-avatar8.jpg");
				Cache::handler()->set(utils::SID($sid), $uid);
				Cache::handler()->hmset(utils::UID($uid), 
					array(
						'uid' => $uid,
						'sid' =>$sid,
						'gold' => 200,
						'name' => $user->nickname,
                        'avatar' => urlencode($avatar),
                ));
	        }
	        $userCache = utils::getUser($uid);
	        if (!array_key_exists('sid', $userCache) or $userCache['sid'] !== $sid)
	        {
	        	Log::error("login sid error");
	        	break;
	        }
	        // $inroomid = array_key_exists('inroomid', $userCache) ? $userCache['inroomid'] : 0;
	        $inroomid = utils::ARR_VAL($userCache, 'inroomid');
	        if ($inroomid)
	        {
	        	$inroom_info = utils::getRoom($inroomid);
	        	if (empty($inroom_info))
	        	{
	        		Cache::handler()->hdel(utils::UID($uid), 'inroomid');
	        		unset($userCache['inroomid']);
	        	}

	        	if ($cmd and !empty($inroom_info)){
	        		$userCache['inroom_info'] = $inroom_info;
	        	}
	        }

    	    return Pack::output(LogicCmd::RES_LOGIN, $userCache);

    	} while(false);
        
    	return Pack::output_fail(LogicCmd::RES_LOGIN, LogicCode::ERR_VERIFY_FAILURE);
    	// return json($params);
    }

    function GetInfo($params)
    {
        $cmd = (int)utils::ARR_VAL($params, 'cmd');
        $sid = utils::ARR_VAL($params, 'sid');
        $uid_to_get = (int)utils::ARR_VAL($params, 'uid');
        
        if (!self::_isLogined($sid))
        {
            return Pack::output_fail(LogicCode::ERR_VERIFY_FAILURE);
        }

        // $uid = (int)Cache::handler()->get(utils::SID($sid));

        $userCache = utils::getUser($uid_to_get);
        if (empty($userCache))
        {
            return Pack::output_fail(LogicCode::ERR_NOT_EXIST_USER);
        }
        return Pack::output($userCache);
    }

	function _isLogined($sid)
    {
        $uid = (int)Cache::handler()->get(utils::SID($sid));
        if (!$uid)
        {
        	return false;
        }

        $sidCurr = Cache::handler()->hget(utils::UID($uid), 'sid');
        if ($sidCurr !== $sid)
        {
        	return false;
        }
        return true;
    }

    function _allocNewRoomId()
    {
    	$roomid = 626121;
    	do
    	{
    		++$roomid;
    		if (!Cache::handler()->exists(utils::ROOM_ID($roomid)))
    		{
    			return $roomid;
    		}
    	}while(true);
    }

    function CreateRoom($params)
    {
    	$cmd = (int)utils::ARR_VAL($params, 'cmd');
    	$sid = utils::ARR_VAL($params, 'sid');
    	$vid = (int)utils::ARR_VAL($params, 'vid');
    	$num = (int)utils::ARR_VAL($params, 'num');
    	if (!$num)
    	{
    		$num = (int)utils::ARR_VAL($params, 'player');
    	}

    	if (!self::_isLogined($sid))
    	{
    		return Pack::output_fail(LogicCode::ERR_VERIFY_FAILURE);
    	}

    	$uid = (int)Cache::handler()->get(utils::SID($sid));
    	$userCache = utils::getUser($uid);

    	$roomid_cur = (int)utils::ARR_VAL($userCache, 'inroomid');
    	if ($roomid_cur)
    	{
    		$inroom_info_cur = utils::getRoom($roomid_cur);
	    	if (!empty($inroom_info_cur))
	    	{
	    		return Pack::output_fail(LogicCode::ERR_ALREADY_IN_ROOM);
	    	}
    	}

    	$gold_need = 2;
    	$gold_curr = utils::ARR_VAL($userCache, 'gold');
    	if (!$gold_curr || $gold_curr < $gold_need)
    	{
    		return Pack::output_fail(LogicCode::ERR_GOLD_NOT_ENOUGH);
    	}
    	$gold_curr -= $gold_need;
    	$userCache['gold'] = $gold_curr;

    	Cache::handler()->hset(utils::UID($uid), 'gold', $gold_curr);


    	$roomid = self::_allocNewRoomId();

    	$roominfo = array(
    		'uid' => $uid,
    		'vid' => $vid,
    		'num' => $num,
    		'roomid' => $roomid
    		);

    	Cache::handler()->hmset(utils::ROOM_ID($roomid), $roominfo);
    	Cache::handler()->expire(utils::ROOM_ID($roomid), 24*3600);

    	$params = array_merge($params, array('roomid' => $roomid));
    	return self::join_room($params);
    	// return Pack::output($roominfo);
    }

    function join_room($params)
    {
    	$cmd = (int)utils::ARR_VAL($params, 'cmd');
    	$sid = utils::ARR_VAL($params, 'sid');

    	$roomid = (int)utils::ARR_VAL($params, 'roomid');

    	if (!$roomid)
		{
			return Pack::output_fail(LogicCode::ERR_PARAMS);	
		}

    	if (!self::_isLogined($sid))
    	{
    		return Pack::output_fail(LogicCode::ERR_VERIFY_FAILURE);
    	}

    	$uid = (int)Cache::handler()->get(utils::SID($sid));
    	$userCache = utils::getUser($uid);

    	$roomid_cur = (int)utils::ARR_VAL($userCache, 'inroomid');
    	if ($roomid_cur && $roomid_cur !== $roomid)
    	{
    		$inroom_info_cur = utils::getRoom($roomid_cur);
	    	if (!empty($inroom_info_cur))
	    	{
	    		return Pack::output_fail(LogicCode::ERR_ALREADY_IN_ROOM);
	    	}
    	}

    	$inroom_info = $roomid ? utils::getRoom($roomid) : 0;
    	if (empty($inroom_info))
    	{
    		Cache::handler()->hdel(utils::UID($uid), 'inroomid');
    		unset($userCache['inroomid']);

    		return Pack::output_fail(LogicCode::ERR_NOT_EXIST);
    	}

    	$num = (int)utils::ARR_VAL($inroom_info, 'num');

    	$find_index = 0;
    	$null_index = 0;
    	for ($i = 1; $i <= $num; ++$i)
		{
			$mkey = utils::MEMBER_KEY($i);
			$uid_tmp = (int)utils::ARR_VAL($inroom_info, $mkey);
			if (!$null_index && !$uid_tmp)
			{
				$null_index = $i;
			}

			if ($uid === $uid_tmp)
			{
				$find_index = $i;
				break;
			}
		}

		if (!$find_index)
		{
			if (!$null_index)
			{
				return Pack::output_fail(LogicCode::ERR_ROOM_FULL);
			}
			
			$memKey = utils::MEMBER_KEY($null_index);
			$inroom_info[$memKey] = $uid;

			Cache::handler()->hset(utils::ROOM_ID($roomid), $memKey, $uid);
			Cache::handler()->expire(utils::ROOM_ID($roomid), 24 * 3600);
		}

		Cache::handler()->hset(utils::UID($uid), 'inroomid', $roomid);
		return Pack::output($inroom_info);
	}

	function exit_room($params)
    {
    	$cmd = (int)utils::ARR_VAL($params, 'cmd');
    	$sid = utils::ARR_VAL($params, 'sid');

    	$roomid = (int)utils::ARR_VAL($params, 'roomid');

  //   	if (!$roomid)
		// {
		// 	return Pack::output_fail(LogicCode::ERR_PARAMS);	
		// }

    	if (!self::_isLogined($sid))
    	{
    		return Pack::output_fail(LogicCode::ERR_VERIFY_FAILURE);
    	}

    	$uid = (int)Cache::handler()->get(utils::SID($sid));
    	$userCache = utils::getUser($uid);

		$roomid_cur = (int)utils::ARR_VAL($userCache, 'inroomid');

		if (!$roomid)
		{
			$roomid = $roomid_cur;
		}

		$inroom_info = $roomid ? utils::getRoom($roomid) : 0;
    	if (empty($inroom_info))
    	{
    		Cache::handler()->hdel(utils::UID($uid), 'inroomid');
    		unset($userCache['inroomid']);

    		return Pack::output_fail(LogicCode::ERR_NOT_EXIST);
    	}

		$num = (int)utils::ARR_VAL($inroom_info, 'num');
    	$find_index = 0;
    	$null_index = 0;
    	for ($i = 1; $i <= $num; ++$i)
		{
			$mkey = utils::MEMBER_KEY($i);
			$uid_tmp = (int)utils::ARR_VAL($inroom_info, $mkey);
			if (!$null_index && !$uid_tmp)
			{
				$null_index = $i;
			}

			if ($uid === $uid_tmp)
			{
				$find_index = $i;
				break;
			}
		}

		if ($find_index)
		{
			$memKey = utils::MEMBER_KEY($find_index);
			unset($inroom_info[$memKey]);

			Cache::handler()->hdel(utils::ROOM_ID($roomid), $memKey);
			Cache::handler()->expire(utils::ROOM_ID($roomid), 24 * 3600);
		}

		if ($roomid_cur)
		{
			Cache::handler()->hdel(utils::UID($uid), 'inroomid');
		}

		return Pack::output($inroom_info);
    }

	function remove_room($params)
    {
    	$cmd = (int)utils::ARR_VAL($params, 'cmd');
    	$sid = utils::ARR_VAL($params, 'sid');

    	$roomid = (int)utils::ARR_VAL($params, 'roomid');

    	if (!$roomid)
		{
			return Pack::output_fail(LogicCode::ERR_PARAMS);	
		}

    	if (!self::_isLogined($sid))
    	{
    		return Pack::output_fail(LogicCode::ERR_VERIFY_FAILURE);
    	}

    	$uid = (int)Cache::handler()->get(utils::SID($sid));
    	$userCache = utils::getUser($uid);

		$inroom_info = $roomid ? utils::getRoom($roomid) : 0;
    	if (empty($inroom_info))
    	{
    		return Pack::output_fail(LogicCode::ERR_NOT_EXIST);
    	}

    	Cache::handler()->del(utils::ROOM_ID($roomid));

    	return Pack::output($inroom_info);
	}
}
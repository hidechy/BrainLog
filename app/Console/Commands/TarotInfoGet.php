<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TarotInfoGet extends Command
{

    protected $signature = 'TarotInfoGet';

    protected $description = 'TarotInfoGet';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        /*
        $url = "https://meigen.keiziban-jp.com/manabi/renai/uranai/tarot-card/";
        $content = file_get_contents($url);
*/


        $content = "

<h3><span id=\"0THE_FOOL\">0.THE FOOL 愚者</span></h3>
<p>　<img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/0.jpg\" alt=\"愚者\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">0.THE FOOL 愚者</span><br />
　キーワード：自由　不安定　素朴<br />
<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>未知の分野でも怖れる前に行動を起こしましょう。今は“見る前に跳べ”あれこれ考えるより先に本当にやってしまうほうが運命を切り開くことができるタイミングです。「何をやってもうまくいく」という根拠のない自信が大事です。</p>
<div class=\"akairo\">逆位置の意味</div><p>状況や空気を読んで、他人の話をよく聞いて行動したい時です。思慮に欠ける無謀な行動は最終的にあなたのためにはならないでしょう。今のままで無理に動けば動くほどうまくいかなくなり、あなたの手には何も残りません。</p>
<h5 class=\"cardright\"><span id=\"i\">タロットカード：大アルカナ</span></h5>

<h3><span id=\"1THE_MAGICIAN\">1.THE MAGICIAN 魔術師</span></h3>
<p>　<img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/1.jpg\" alt=\"魔術師\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">1.THE MAGICIAN 魔術師</span><br />
　キーワード：恋のはじまり　創意工夫　優柔不断<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>創造的な才能やセンスを活かすことで物事がうまくいく時です。錬金術師は無から有を生み出すのではなく、その魔術によって元素を金に変えることができるように、あなたの中にも状況をもっと自分らしくアレンジする力があります。</p>
<div class=\"akairo\">逆位置の意味</div><p>自信を失い優柔不断に傾きがちなようです。創造的な力をうまく発揮できなくて問題を乗り越えられないという消極性が支配しているようです。真剣に悩んでいるというよりも、口先だけで困っているような印象を与えます。</p>
<h5 class=\"cardright\"><span id=\"i-2\">タロットカード：大アルカナ</span></h5>

<h3><span id=\"2THE_HIGE_PRIESTESS\">2.THE HIGE PRIESTESS 女教皇</span></h3>
<p>　<img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/2.jpg\" alt=\"女教皇\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">2.THE HIGE PRIESTESS 女教皇</span><br />
　キーワード：神秘的　内省的　神経質<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>高い知力を持つ女性が問題に関わっているようです。頭脳明晰なので行動力には欠ける面があって、進展には時間がかかるでしょう。実行よりも頭の中でいろいろ考えることを優先する傾向があり精神的な面を重視します。</p>
<div class=\"akairo\">逆位置の意味</div><p>理知的な反面、心に余裕を欠いてしまうと周囲に対してそのままイライラをぶつけてしまう面が強調されます。頭でっかちで知識を重視しがちなので、周囲には“冷たい人だ”と誤解されがちです。自分から愛情を表現しません。</p>
<h5 class=\"cardright\"><span id=\"i-3\">タロットカード：大アルカナ</span></h5>

<h3><span id=\"3THE_EMPRESS\">3.THE EMPRESS 女帝</span></h3>
<p>　<img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/3.jpg\" alt=\"女帝\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">3.THE EMPRESS 女帝</span><br />
　キーワード：豊かさ　贅沢　母性<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>今はすべての面で満たされて何ひとつ欠けているものがありません。幸福の頂点と言えます。欲しいものは楽々と手に入るでしょうし、「実り」を実感しやすい時期です。相手は家族に持つような深い愛情を持っています。</p>
<div class=\"akairo\">逆位置の意味</div><p>豊かさが裏目に出ると、意志の弱さや欲張りになってしまいます。現状に満足できないままに“もっと欲しい”気持ちが募り、欲望のコントロールがかなり難しい時期。独占欲が募れば強い嫉妬に苦しめられることになります。</p>
<h5 class=\"cardright\"><span id=\"i-4\">タロットカード：大アルカナ</span></h5>

<h3><span id=\"4THE_EMPEROR\">4.THE EMPEROR 皇帝</span></h3>
<p>　<img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/4.jpg\" alt=\"皇帝\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">4.THE EMPEROR 皇帝</span><br />
　キーワード：成功　責任　自信<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>成功を得れば社会的な責任もついてきます。リーダーになった暁には自分の正直な気持ちのままに振舞うことは到底許されず、リーダーとして振舞うことを要求されるのです。社会的勝ち組と呼ばれてもどこか寂しそうです。</p>
<div class=\"akairo\">逆位置の意味</div><p>自分の能力や権力に対する自信が悪い形で出るようになります。ワンマンさが目立ち、それが周囲の人との距離を広げます。未熟な人間であるという謙遜さを忘れ、威張り散らしてしまうために自信過剰な人という印象を与えます。</p>
<h5 class=\"cardright\"><span id=\"i-5\">タロットカード：大アルカナ</span></h5>

<h3><span id=\"5THE_HIEROPHANT\">5.THE HIEROPHANT 法王</span></h3>
<p>　<img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/5.jpg\" alt=\"法王\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">5.THE HIEROPHANT 法王</span><br />
　キーワード：慈しみ　寛大な精神　偏狭<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>状況を改善するためには、無条件の慈しみとか弱い立場の人に対する寛大な精神が求められているようです。本当に困っているときにそっと力を貸してくれるような目上の存在です。穏やかに相手を受け止めることが大事です。</p>
<div class=\"akairo\">逆位置の意味</div><p>成功するためには社会のモラルやマナーを順守することが大事です。マナーを欠いた行動は最終的にあなたにとって損になるので、“自分さえよければいい”という視野の狭い考え方を反省する時です。偏狭になっていませんか。</p>
<h5 class=\"cardright\"><span id=\"i-6\">タロットカード：大アルカナ</span></h5>
<h3><span id=\"6THE_LOVERS\">6.THE LOVERS 恋人</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/6.jpg\" alt=\"恋人\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">6.THE LOVERS 恋人</span><br />
　キーワード：軽い　楽しい　明るい<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>軽やかで明るくて綺麗で楽しい…そんな恋に落ちたばかりのころのときめきとウキウキするような予感に満ちています。人間関係は楽しく、毎日は華やかな夢の一幕のようです。もっと楽しむためには“今は深く追求しないこと”</p>
<div class=\"akairo\">逆位置の意味</div><p>軽いノリで、刹那的な快楽を追い求めると、後でやけどをしたりします。もともと軽いムードだったのが、また一層と軽くなったようです。そのためすべてにおいて浮気っぽく、同時に複数の選択肢に目移りしたりします。</p>
<h5 class=\"cardright\"><span id=\"i-7\">タロットカード：大アルカナ</span></h5>
<h3><span id=\"7THE_CHARIOT\">7.THE CHARIOT 戦車</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/7.jpg\" alt=\"戦車\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">7.THE CHARIOT 戦車</span><br />
　キーワード：迅速　前進　逃げ腰<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>状況はあなたが想像しているよりスピーディに展開します。本当にあっと言う間にあなたにとって望ましい方向に前進します。だからあなたから今行動する意味があるのです。“先へ進もう”と、はやる気持ちを抑えきれません。</p>
<div class=\"akairo\">逆位置の意味</div><p>相手は逃げ腰です。あなたの勢いに押されて引いてしまったのかもしれません。状況はぴたっと一時停止してしまったかのようです。相手は迷いや動揺のただなかにいるようです。スピード違反の切符を切られないように注意！</p>
<h5 class=\"cardright\"><span id=\"i-8\">タロットカード：大アルカナ</span></h5>
<h3><span id=\"8STRENGTH\">8.STRENGTH 力</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/8.jpg\" alt=\"力\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">8.STRENGTH 力</span><br />
　キーワード：意志　行動　有言実行<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>有言実行が求められます。状況を打破するためには強い意志が必要な時です。他人にまかせて自分は傍観していればうまくいく…なんて甘い考えでは成功できませんが、あなたは潜在的に力強くエネルギッシュな人間です。</p>
<div class=\"akairo\">逆位置の意味</div><p>かなり強い脱力感を味わうことに。無理だと感じながらも頑張ってもうまくいかないことも人生には往々にしてあります。自信が揺らいで地の底まで落ち込むかもしれません。考えすぎると心を病んでしまいますから要注意。</p>
<h5 class=\"cardright\"><span id=\"i-9\">タロットカード：大アルカナ</span></h5>
<h3><span id=\"9THE_HERMIT\">9.THE HERMIT 隠者</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/9.jpg\" alt=\"隠者\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">9.THE HERMIT 隠者</span><br />
　キーワード：沈黙　静穏　内省<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>気持ちがあっても表現できない時があります。表現してはいけない相手かもしれません。しかし真面目で真摯な愛情は内に秘めているのです。じっと黙って、頭上の感情の嵐が通り過ぎるのを孤独に待っていますから静穏です。</p>
<div class=\"akairo\">逆位置の意味</div><p>誰かとつながりたい、わかち合いたいという気持ちはあるのに、自分を変えるくらいならずっと孤独でいたほうがいい、そのほうが気楽である…と深い孤独に傾きがちな時です。好きだという気持ちがあっても認めようとしないでしょう。</p>
<h5 class=\"cardright\"><span id=\"i-10\">タロットカード：大アルカナ</span></h5>
<h3><span id=\"10WHEEL_of_FORTUNE\">10.WHEEL of FORTUNE 運命の輪</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/10.jpg\" alt=\"運命の輪\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">10.WHEEL of FORTUNE 運命の輪</span><br />
　キーワード：チャンス到来　大きな変化　一時的な現象<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>これまでの関係が大きく変わる時です。なにかの問題を自覚しているのなら、その問題の解決について状況が大きく変化することが期待できます。あなたにとって望ましい方向へ変化するのですが、チャンスは一度きりかも。</p>
<div class=\"akairo\">逆位置の意味</div><p>良いことも悪いことも一時的なものです。もしかしたらあなたは関係が急速に冷えていってしまっていると感じているかもしれませんが、すべての関係はつねに変化し続けるし、悪くなったとしてもそれも一時的な現象です。</p>
<h5 class=\"cardright\"><span id=\"i-11\">タロットカード：大アルカナ</span></h5>
<h3><span id=\"11JUSTICE\">11.JUSTICE 正義</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/11.jpg\" alt=\"正義\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">11.JUSTICE 正義</span><br />
　キーワード：バランス　客観的　平常心<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>平常心を保つこと、感情のコントロールが必要な時です。バランスのとれた客観的な見方ができなければ、相手の言葉やメールを深読みしてしまったり、無駄に感情を消耗してしまうことに。冷静に事態を把握しましょう。</p>
<div class=\"akairo\">逆位置の意味</div><p>心中動揺すると、客観的に状況をつかむのが難しくなります。だからといって真面目に考え込すぎると余裕や色気が無くなってしまいます。二つの選択肢を両方ともつかんで、どちらも中途半端になったりするのは避けましょう。</p>
<h5 class=\"cardright\"><span id=\"i-12\">タロットカード：大アルカナ</span></h5>
<h3><span id=\"12THE_HANGED_MAN\">12.THE HANGED MAN 吊し人</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/12.jpg\" alt=\"吊し人\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">12.THE HANGED MAN 吊し人</span><br />
　キーワード：試練　忍耐　献身<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>幸福をつかむための試練の時。苦しい気持ちがあってもじっと我慢することで、後日大きな実りをもたらしてくれるかもしれません。試練は厳しいかもしれませんが、大変な辛さを乗り越えた暁には手応えを感じるでしょう。</p>
<div class=\"akairo\">逆位置の意味</div><p>やることなすこと裏目に出やすいタイミングです。骨折り損のくたびれ儲けに終わる可能性が高いです。報われない苦痛を強いられているようです。ちょっと状況を振り返ってみて、本当にそんな忍耐が必要なのでしょうか？</p>
<h5 class=\"cardright\"><span id=\"i-13\">タロットカード：大アルカナ</span></h5>
<h3><span id=\"13DEATH\">13.DEATH 死</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/13.jpg\" alt=\"DEATH\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">13.DEATH 死</span><br />
　キーワード：衰退　変化　終了<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>生きている限り状況は変わり続けます。変化しないものは無いのですが、人間関係においても盛り上がる時と、衰退していく時があります。残念ながら縁が薄れていく時です。しかし終わらなければ始めることもできません。</p>
<div class=\"akairo\">逆位置の意味</div><p>終わりにしたいのに終わることができない、腐れ縁になっているのかもしれません。この関係が本当にあなたにとって有意義なものかどうか？見直す時です。衰退した仲を断ち切ることでまったく新しい世界を体験できます。</p>
<h5 class=\"cardright\"><span id=\"i-14\">タロットカード：大アルカナ</span></h5>
<h3><span id=\"14TEMPERANCE\">14.TEMPERANCE 節制</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/14.jpg\" alt=\"節制\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">14.TEMPERANCE 節制</span><br />
　キーワード：相性のよさ　自然さ　穏やかさ<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>あれこれ考えなくても、放っておくだけで上手く物事が流れていきます。自分から積極的に働きかけをする必要もなく、一緒にいるだけで愉しいし、気持ちが安らぎ、ほっとできます。相手もまさにそう感じているのです。</p>
<div class=\"akairo\">逆位置の意味</div><p>新鮮さを感じることができず、マンネリに陥っているようです。一緒にいても盛り上がらないとか、違和感を感じる発言が続きます。“この人じゃないのかも…”という失望が広がります。だらけた刺激の乏しい状態です。</p>
<h5 class=\"cardright\"><span id=\"i-15\">タロットカード：大アルカナ</span></h5>
<h3><span id=\"15THE_DEVIL\">15.THE DEVIL 悪魔</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/15.jpg\" alt=\"悪魔\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">15.THE DEVIL 悪魔</span><br />
　キーワード：堕落　誘惑　魅了<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>誘惑に負けて、堕落した世界のとりこになっている状態です。あってはならない状況です。許されざる快楽に身をゆだねてどこへも逃れられなくなっているのです。ここにいる限り、人間として成長することはありません。</p>
<div class=\"akairo\">逆位置の意味</div><p>苦痛で仕方なかった状態から解放されてほっと一息つけそうです。あの束縛から解放されたのです。長い間、悩み続けてきた関係が楽になる時です。あなたにとって望ましい方向へ状況は変化することを実感できるでしょう。</p>
<h5 class=\"cardright\"><span id=\"i-16\">タロットカード：大アルカナ</span></h5>
<p>　<br />
<h3><span id=\"16THE_TOWER\">16.THE TOWER 塔</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/16.jpg\" alt=\"塔\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">16.THE TOWER 塔</span><br />
　キーワード：崩壊　衝撃　緊迫<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>致命的な失敗や衝撃が生じたようです。これまで維持してきた関係があっという間に崩れ去るほどネガティブな衝撃です。今まで信じてきた何かや関係に大きなダメージが発生しました。あなたは警戒する必要があります。</p>
<div class=\"akairo\">逆位置の意味</div><p>何かがダメになるという予感が心を支配しているのなら、間もなくそれは現実になるでしょう。強い予感が予感に終わらず、緊迫した状態が続きます。警戒心を最大にしてもまだ足りないというほどの不安感や緊張感です。</p>
<h5 class=\"cardright\"><span id=\"i-17\">タロットカード：大アルカナ</span></h5>
<h3><span id=\"17THE_STAR\">17.THE STAR 星</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/17.jpg\" alt=\"星\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">17.THE STAR 星</span><br />
　キーワード：安らぎ　憧れ　理想<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>非現実的な美しさや夢や優しさに触れて、心なごんだり深い安らぎを感じています。夢のつづきが覚醒しても続いているかのような優しさが心を支配します。全身でリラックスできます。うっとりとしたまま恍惚を感じています。</p>
<div class=\"akairo\">逆位置の意味</div><p>まるで夢から醒めたようながっかりした気持ちです。幻滅しているのです。現実の厳しさを思い知ることになります。自分の何か、感情やお金や時間を注ぎ込みすぎ、投資しすぎ、与えすぎになっていないかどうか注意を。</p>
<h5 class=\"cardright\"><span id=\"i-18\">タロットカード：大アルカナ</span></h5>
<h3><span id=\"18THE_MOON\">18.THE MOON 月</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/18.jpg\" alt=\"月\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">18.THE MOON 月</span><br />
　キーワード：不安　焦り　先行き不透明<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>夜の間に形を変え続ける月のように、心の焦点も定まらず、落ち着かず、不安や焦りが支配しがちな時です。結果や先行きが不透明で、月に映る表情のようにまるで現実味が乏しいのです。もやもやとした感覚が支配します。</p>
<div class=\"akairo\">逆位置の意味</div><p>誤解していたことに気が付き自分でほっとします。不安や焦りから解放されて、すっきりとした視界を取り戻すことができます。物事が順調に進んでいたことを知ることになります。混沌としたムードが次第に弱くなります。</p>
<h5 class=\"cardright\"><span id=\"i-19\">タロットカード：大アルカナ</span></h5>
<h3><span id=\"19THE_SUN\">19.THE SUN 太陽</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/19.jpg\" alt=\"太陽\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">19.THE SUN 太陽</span><br />
　キーワード：明るい　エネルギッシュ　成功<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>エネルギッシュなやりとりを繰り返すことで、何をやってもうまくいって、屈託なく明るい状況が訪れるでしょう。強い情熱を感じ、明るく開放的なムードがお互いの心を満たします。屋内より戸外、アウトドアがよいでしょう。</p>
<div class=\"akairo\">逆位置の意味</div><p>そこそこ上手くいくでしょう。エネルギー不足が否めないようです。あなたの願望の８０％くらいしか叶わないかもしれません。この状況を打破するにはエネルギーを蓄える必要がありそうです。少し自信が無いようです。</p>
<h5 class=\"cardright\"><span id=\"i-20\">タロットカード：大アルカナ</span></h5>
<h3><span id=\"20JUDGEMENT\">20.JUDGEMENT 審判</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/20.jpg\" alt=\"審判\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">20.JUDGEMENT 審判</span><br />
　キーワード：復活　復縁　迅速<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>半分あきらめていた関係が復縁できるかもしれません。一度過去に終わった関係が再び燃え上がることも今なら無理ではありませんし、状況はあなたが驚くほどスピーディに進展していくようです。周囲に応援を頼みましょう。</p>
<div class=\"akairo\">逆位置の意味</div><p>心中では“やりなおせるのではないか”…と期待していたのなら、その期待は残念ながら裏切られるでしょう。覆水盆に返らず、無理な期待や邪な願望を抱かないほうが良い時です。何か厳しい状況が訪れる前触れが生じます。</p>
<h5 class=\"cardright\"><span id=\"i-21\">タロットカード：大アルカナ</span></h5>
<h3><span id=\"21THE_WORLD\">21.THE WORLD 世界</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/21.jpg\" alt=\"世界\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">21.THE WORLD 世界</span><br />
　キーワード：完成　理想　惰性<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>完成された世界がもたらす幸福感に満たされる時です。到達しうる最高レベルの幸福感ですから完全無欠です。精神的な意味での成功を意味するので、金銭や競争がもたらす欲とは無縁です。あふれる幸福感が支配します。</p>
<div class=\"akairo\">逆位置の意味</div><p>何かひとつ欠けていて、結婚願望は無いようです。あなたと結婚する気が無いのか？それとも結婚という関係自体に興味が無いのかは相手次第ですが。惰性に流されるまま関係を続けていても、あまり意味が無いかもしれません。</p>
<h5 class=\"cardright\"><span id=\"i-22\">タロットカード：大アルカナ</span></h5>

<h3><span id=\"Ace_of_Wand\">ワンドのＡ(Ace of Wand)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/w1.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ワンドのＡ(Ace of Wand)</span><br />
　キーワード：始まり　情熱的　創造<br />
　　<br />
<ins class=\"adsbygoogle\"
style=\"display:block; text-align:center;\"
data-ad-layout=\"in-article\"
data-ad-format=\"fluid\"
data-ad-client=\"ca-pub-1941825262285936\"
data-ad-slot=\"9043719496\"></ins>
<script>(adsbygoogle=window.adsbygoogle||[]).push({});</script>
<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>創造的なエネルギーによって物事をスタートさせる時です。新しく何かを始めるのに最もふさわしいタイミングです。相手も大いに乗り気であり、成功するために必要な力や環境はすべていまのあなたに備わっているのです。</p>
<div class=\"akairo\">逆位置の意味</div><p>激しい失意を味わうことになり、物事が完全に終了します。何か企画している案はうまくいかないで挫折しやすい時です。自分に力があってもその力をうまくコントロールすることが難しく、やる気だけが空回りしています。</p>
<h5 class=\"cardright\"><span id=\"i-23\">タロットカード：小アルカナ</span></h5>

<h3><span id=\"Two_of_Wands\">ワンドの２(Two of Wands)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/w2.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ワンドの２(Two of Wands)</span><br />
　キーワード：野心　成功　犠牲<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>遠い先を見つめて未来を楽観しています。それ相応の犠牲を払うのなら、成功することができそうです。社会的に到達しうる地位に上り詰めることだって可能でしょう。野心を抱いて海外での活躍を見つめているようです。</p>
<div class=\"akairo\">逆位置の意味</div><p>成功しようと思えば、誰でもそれ相応の犠牲を伴います。働きすぎて心身を壊す可能性だってあるでしょう。二者択一で切ったほうの選択肢に内心、迷い続けることだってあるのです。背を向けた何かに後ろ髪を引かれるかも。</p>
<h5 class=\"cardright\"><span id=\"i-24\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Three_of_Wands\">ワンドの３(Three of Wands)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/w3.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ワンドの３(Three of Wands)</span><br />
　キーワード：展望　遠方　期待<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>高いところに立って地平を眺めているように、明るい未来を展望しています。海外での貿易や取引、ビジネスに縁があります。将来を展望していると同時に、新しい旅立ちの予感がしています。意欲がみなぎってくるようです。</p>
<div class=\"akairo\">逆位置の意味</div><p>遠い眺めを見つめて、明るい今後を期待しています。良い展開になることを疑っていないのです。離れていても、一緒にいても、ずっと見守ってくれるという後見人やバックアップも期待できるでしょう。遠方に縁があります。</p>
<h5 class=\"cardright\"><span id=\"i-25\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Four_of_Wands\">ワンドの４(Four of Wands)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/w4.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ワンドの４(Four of Wands)</span><br />
　キーワード：歓迎　招待　門出<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>一緒にいて楽しいし、歓迎されています。お祝い事に招待された楽しいゲストのようなひとときを過ごすことができるでしょう。相性のよさや居心地の良さを実感することができます。受け入れられてリラックスできるのです。</p>
<div class=\"akairo\">逆位置の意味</div><p>受け入れてもらえず、無理に入っても違和感を感じたり、疎外感に悩まされそうです。空いているのに入れない状態です。離れるべき時期なのに、出発が遅れているようです。同郷の仲間同志のはずなのにうまくいきません。</p>
<h5 class=\"cardright\"><span id=\"i-26\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Five_of_Wands\">ワンドの５(Five of Wands)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/w5.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ワンドの５(Five of Wands)</span><br />
　キーワード：忙しい　慌ただしい　慌てる<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>バタバタと慌ただしい状態です。忙しいというか、本人も状況の把握ができなくなるほど慌ただしい時間を過ごしていて、複数の案件や人物が同時に関わっていることもあって、わけがわからなくなっているのかもしれません。</p>
<div class=\"akairo\">逆位置の意味</div><p>紛争やトラブルが自然におさまったり、意見や考え方がバラバラだったのが時間が経つにつれて一致してきたりするようです。あるいは一時休戦みたいな感じで足踏み状態が続きます。訴訟・談合に負ける可能性もあります。</p>
<h5 class=\"cardright\"><span id=\"i-27\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Six_of_Wands\">ワンドの６(Six of Wands)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/w6.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ワンドの６(Six of Wands)</span><br />
　キーワード：勝利　前進　自信<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>誇らしげで目立つ存在になれそうです。勝ち誇っていて自信に満ちているそんな勝利の印象を周囲に与えています。仲間と協力しながら物事を進めることで成功をおさめそうです。将来に対して、投資すると良い時期です。</p>
<div class=\"akairo\">逆位置の意味</div><p>自信を持つことができないで、優柔不断になっているとか、負けること・結果を出せないことを極端に恐れてためらったり、足踏みしている状態です。他の人たちの応援も期待できず、孤立無援で戦うことを迫られそうです。</p>
<h5 class=\"cardright\"><span id=\"i-28\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Seven_of_Wands\">ワンドの７(Seven of Wands)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/w7.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ワンドの７(Seven of Wands)</span><br />
　キーワード：有利な立場　独り勝ち　差別化<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>有利な立場にいるようです。圧倒的に独り勝ちしている、ライバルに差をつけつつある、どういう勝負であっても立場が強い方にいる、差別化に成功する、才能や能力が抜きんでているという状態、つねに上位にいるようです。</p>
<div class=\"akairo\">逆位置の意味</div><p>追い詰められていて、苦戦を強いられているようです。まったく気を抜くことができず、ここでやられると後がないことを知っている、味方もいないのに孤軍奮闘を続けています。不利な戦い・低レベルなやりとりを続けています。</p>
<h5 class=\"cardright\"><span id=\"i-29\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Eight_of_Wands\">ワンドの８(Eight of Wands)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/w8.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ワンドの８(Eight of Wands)</span><br />
　キーワード：変化　改善　遠方<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>状況がかなりのスピードで変化して、あなたの望む方向へ進んでいくようです。今は勢いがありますから再度のチャンレンジでうまくいきます。時間が経てば改善していくでしょう。遠方の土地や人物に縁がありそうです。</p>
<div class=\"akairo\">逆位置の意味</div><p>状況は停滞していて、方向感は分からず、どちらへ変化していくのか読めません。活発ではない、動きがなく、勢いが衰え、マンネリ化していくようです。時間が長く感じられるし、結果待ちのようななすすべもない気持ちです。</p>
<h5 class=\"cardright\"><span id=\"i-30\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Nine_of_Wands\">ワンドの９(Nine of Wands)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/w9.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ワンドの９(Nine of Wands)</span><br />
　キーワード：警戒心　慎重さ　緊張感<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>過去の失敗や傷やトラウマが原因になって、警戒心がかなり強くなってビクビクしています。また失敗するのでは…と、慎重すぎて行動が起こせません。相手の出方を伺ってばかりで緊張状態が続いていて防御を固めています。</p>
<div class=\"akairo\">逆位置の意味</div><p>慎重すぎて行動できなくなって、チャンスを逃します。逆に警戒心が足りなくて、無防備すぎて敵につけいる隙を与えているようです。出遅れてしまって不意打ちにあうとか、動けなくて何もできなくなっているようです。</p>
<h5 class=\"cardright\"><span id=\"i-31\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"10Ten_of_Wands\">ワンドの10(Ten of Wands)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/w10.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ワンドの10(Ten of Wands)</span><br />
　キーワード：容量オーバー　プレッシャー　投げ出す<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>余裕がなくて、容量オーバーなので、これ以上は抱えきれないと、強いプレッシャーを感じてるようです。最後まで支えきる自信が危うくなってきています。苦痛に一人で耐えていますが、ゴールまでもう少しかもしれません。</p>
<div class=\"akairo\">逆位置の意味</div><p>これまで抱え込んできたものの重さに耐えられなくなって、手放すかもしれません。一人じゃ支えきれなくて、途中で不本意ながら投げ出すとか、我慢の限界に達して、これ以上頑張ると壊れてしまうからやめてしまうようです。</p>
<h5 class=\"cardright\"><span id=\"i-32\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Page_of_Wands\">ワンドの王子(Page of Wands)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/w11.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ワンドの王子(Page of Wands)</span><br />
　キーワード：良い知らせ　明るい　純粋<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>明るく天真爛漫で、純粋な状態です。子供らしいかわいらしさに満ちています。仕事や勉学に関しては特に良く、あなたにとって望ましい変化や嬉しくなるような知らせが舞い込むでしょう。メッセンジャーという意味も。</p>
<div class=\"akairo\">逆位置の意味</div><p>はしゃぎすぎて失敗したり、無茶をして怪我をしたり、かまってほしくて悪ふざけをするような子供っぽい態度が目立ちます。とにかく他人の注目を集めたがります。仕事や勉学に関して、悪い知らせが届くこともあります。</p>
<h5 class=\"cardright\"><span id=\"i-33\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Knight_of_Wands\">ワンドの騎士(Knight of Wands)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/w12.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ワンドの騎士(Knight of Wands)</span><br />
　キーワード：前進　出発　旅行<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>目標を掲げて前進することができます。道は遠かったり険しいこともあるでしょうが、強い情熱があれば難なく乗り切ることができるでしょう。何もおそれずに未知の世界や関係に向かって出発する時です。旅行が吉です。</p>
<div class=\"akairo\">逆位置の意味</div><p>前進したい、先へ進みたいという情熱はあるのに、何かと邪魔が入るので、動揺をおさえることができない状態です。突然地へ叩きつけられるような、想定外の妨害が入ることもあります。うまく周囲と協調できないのかもしれません。</p>
<h5 class=\"cardright\"><span id=\"i-34\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Queen_of_Wands\">ワンドの女王(Queen of Wands)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/w13.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ワンドの女王(Queen of Wands)</span><br />
　キーワード：情熱的　エネルギッシュ　負けず嫌い<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>情熱的で、エネルギッシュで、堂々とした態度で、周囲を圧倒します。じっとしていられず、自由に行動するようです。誰に対しても遠慮などせず、正面から自分の意見を言ったり、他人を仕切ったり、リードしたりすることを好みます。</p>
<div class=\"akairo\">逆位置の意味</div><p>負けず嫌いなので、わがままに走りがちです。もっと周囲に感謝の気持ちを持つべきなのですが他人の話を素直に聞けないのです。強い嫉妬で自分が悩んだり周囲を悩ませたりします。やる気が空回りすることもあります。</p>
<h5 class=\"cardright\"><span id=\"i-35\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"King_of_Wands\">ワンドの王(King of Wands)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/w14.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ワンドの王(King of Wands)</span><br />
　キーワード：情熱的　行動力　視野が狭い<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>情熱的で自信のある態度で、未来へ向かって行動します。楽観的で、行動力があり、強い情熱とともにもっと向上したいという気持ちも強いようです。自己啓発には熱心なのですが社会への関心は乏しい傾向もみられます。</p>
<div class=\"akairo\">逆位置の意味</div><p>視野が狭く、ワンマンな態度、強引さが目立ちます。横柄で、周囲の反応をあまり気にしません。自分のことしか考えられない人という印象を周囲に与えているようです。行動力があるだけに求心力や人望を落としがちです。</p>
<h5 class=\"cardright\"><span id=\"i-36\">タロットカード：小アルカナ</span></h5>

<h3><span id=\"Ace_of_Cup\">カップのＡ(Ace of Cup)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/c1.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">カップのＡ(Ace of Cup)</span><br />
　キーワード：愛のはじまり　幸福感　豊かな感性<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>いくら注いでも溢れてくる愛情で幸福感いっぱいです。愛情のはじまりであり、結婚や妊娠、出産や女性の幸福一般を意味することもあります。感性が豊かで、器が大きいのでどんな物事のどんな側面も受け入れてしまえるのです。</p>
<div class=\"akairo\">逆位置の意味</div><p>愛を注ぎ込みすぎてもはや何も残っていません。与えすぎ、求めすぎに注意しましょう。情に流されやすかったり、逆に過去に傷ついた経験から愛することに臆病になっていたりします。何でもそのまま受け入れてしまう面も。</p>
<h5 class=\"cardright\"><span id=\"i-37\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Two_of_Cups\">カップの２(Two of Cups)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/c2.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">カップの２(Two of Cups)</span><br />
　キーワード：甘い恋の予感　距離が縮まる　ときめき<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>若い男女がお互いに寄り添い、愛をささやきあっているようです。まだお互いをよく知らないので恋の予感が気持ちをときめかせます。安定感はいまいちですが、しかしこの先きっと幸福になれるという楽観的な気持ちです。</p>
<div class=\"akairo\">逆位置の意味</div><p>片思いなら両思いになるのは難しく、相思相愛だというのなら相手の気持ちが離れていく時です。人間関係が原因になって意見があわなくなって、付き合いが疎遠になっていくようです。次第に気持ちが遠のいていきます。</p>
<h5 class=\"cardright\"><span id=\"i-38\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Three_of_Cups\">カップの３(Three of Cups)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/c3.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">カップの３(Three of Cups)</span><br />
　キーワード：無邪気　楽しい　心弾む<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>無邪気で楽しい雰囲気が伝わってきます。ウキウキと心躍る時間を過ごすことができそう。考えすぎたり深刻な空気はなくて、ただ日常をすっかり忘れて、明るく軽やかに宴を楽しんでいるようです。集中力には欠けます。</p>
<div class=\"akairo\">逆位置の意味</div><p>快楽に耽ったり、遊び過ぎた後でその疲れやダメージが気になります。さぼり続けたツケを支払う時がきたようです。「妊娠」という意味もありますから、刹那的な関係に溺れて妊娠したりしないように注意する必要があります。</p>
<h5 class=\"cardright\"><span id=\"i-39\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Four_of_Cups\">カップの４(Four of Cups)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/c4.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">カップの４(Four of Cups)</span><br />
　キーワード：倦怠　退屈　小休止<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>何不自由なく物質的に満たされている毎日ですが、今の生活に倦怠感を覚えているようです。刺激もスピードもないので退屈です。何もかもにうんざりして、飽き飽きしているので、マンネリや不満を感じることが多そうです。</p>
<div class=\"akairo\">逆位置の意味</div><p>これまでとは全く違った新しい展開や、新しい人間関係などがつくりやすい時です。停滞もしくは倦怠感を覚えていた日常に新しい局面が訪れます。それによってあなたは日常生活に新鮮な感覚を取り戻すことができます。</p>
<h5 class=\"cardright\"><span id=\"i-40\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Five_of_Cups\">カップの５(Five of Cups)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/c5.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">カップの５(Five of Cups)</span><br />
　キーワード：半分以上の損失　遺産　遺伝<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>半分以上は失ってしまい落胆しています。期待していたことは、半分いや三分の一くらいしか叶わずがっかりしています。ある程度の遺産が相続できるようです、親の援助が期待できます。養子縁組は吉です。遺伝が関係しています。</p>
<div class=\"akairo\">逆位置の意味</div><p>少しづつ視界が明るくなっていきます。やや状況が改善しそうです。養子縁組は解消されます。カップルが別れる、夫婦が別居することになります。恋人と別れることで新しい展開を期待できます。独立には良い時期です。</p>
<h5 class=\"cardright\"><span id=\"i-41\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Six_of_Cups\">カップの６(Six of Cups)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/c6.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">カップの６(Six of Cups)</span><br />
　キーワード：過去　子供　平和的<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>初恋や初体験をともにした相手の面影をぬぐうことができません。子供のころの出来事や思い出にとらわれているようです。過ぎ去った日のことが忘れられません。平和的で和気藹々としていますが発展的ではありません。</p>
<div class=\"akairo\">逆位置の意味</div><p>過去を振り切ることで未来に新しい可能性を見出すことができます。過去にこだわらないことで新しい成功に向けて歩き始めます。新しく物事をスタートさせる時です。未来に向かって進みます。生まれ変わるという意味もあります。</p>
<h5 class=\"cardright\"><span id=\"i-42\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Seven_of_Cups\">カップの７(Seven of Cups)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/c7.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">カップの７(Seven of Cups)</span><br />
　キーワード：空想　夢想　非現実的<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>空想が広がり、現実を正視することが難しくなります。夢ばかりが広がり正体がわかりません。大きな夢を追っているようです。望みが高すぎて足元が見えていません。妄想や空想が肥大してなにがなんだかわかりません。</p>
<div class=\"akairo\">逆位置の意味</div><p>空想や夢想から醒めてすっきりと現実的な思考ができるようになります。大きな目標も小さな一歩から歩き出していけます。五里霧中のようだった視界も晴れて客観視することが可能です。暗い幻想に取りつかれるという意味も。</p>
<h5 class=\"cardright\"><span id=\"i-43\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Eight_of_Cups\">カップの８(Eight of Cups)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/c8.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">カップの８(Eight of Cups)</span><br />
　キーワード：別れ　捨て去る　退去<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>別れの潮時です。古いものを捨て去ることによって状況が好転します。断念したり、関係を終えたりすることを強いられそうです。仕事や受験などでは思うような結果が出せません。今いるところから撤退を余儀なくされそうです。</p>
<div class=\"akairo\">逆位置の意味</div><p>祝杯をあげたくなるような幸福な出来事が生じるようです。躍り上がりたくなるような良い出来事が目の前に現れるでしょう。それによってあなたは幸福感に包まれたり、復縁や再婚の望みが叶います。昇進できるでしょう。</p>
<h5 class=\"cardright\"><span id=\"i-44\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Nine_of_Cups\">カップの９(Nine of Cups)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/c9.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">カップの９(Nine of Cups)</span><br />
　キーワード：満足　幸福　夢の実現<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>愛情や物質について最高の満足を得ることができそうです。恵まれています。大きな利益が得られます。正位置で出た場合はウィッシュカードwishcardと呼ばれて、あなたが期待していることが叶う…という暗示になっています。</p>
<div class=\"akairo\">逆位置の意味</div><p>欲張りすぎ、詰め込み過ぎると後で自分が損をすることに。一時的な障害やトラブルに見舞われるかもしれません。損をしたり、不満を抱えたりすることもありますが、自分が欲張っていないかどうか？見直しが必要です。</p>
<h5 class=\"cardright\"><span id=\"i-45\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"10Ten_of_Cups\">カップの10(Ten of Cups)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/c10.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">カップの10(Ten of Cups)</span><br />
　キーワード：家族的な愛　絆　疎外感<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>家族のような温かい絆に包まれて幸福を実感できます。素晴らしい家庭生活、夫婦関係、うまくいく恋愛や人間関係になりそうです。良い職場、よい住居です。恵まれた人間関係ですから、家族の一員として幸福に満たされます。</p>
<div class=\"akairo\">逆位置の意味</div><p>人間の愛情に飢えて疎外感を感じます。自分だけが集団から外れて、孤独であり、誰も信頼できないというような孤独に陥ります。家庭の中に居場所が見当たらず、暴力行為があったり、ワンマンな家長に支配されているのです。</p>
<h5 class=\"cardright\"><span id=\"i-46\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Page_of_Cups\">カップの王子(Page of Cups)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/c11.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">カップの王子(Page of Cups)</span><br />
　キーワード：純粋　無垢　子供っぽい<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>どこか女性的で無垢で子供のような幼い面があります。人を疑ったりしないし、ロマンチストで、甘えるのが好きです。純粋で素直な性格で、あなたに甘えたいという気持ちが強いです。「良い知らせ」という意味があります。</p>
<div class=\"akairo\">逆位置の意味</div><p>愛情に関して「悪い知らせ」という意味があります。未熟で幼いのが原因で失敗する可能性が高いです。甘えたいという気持ちが依存心の強さとなるので、何でも他人に丸投げしたり、刹那的な行動を繰り返すようになります。</p>
<h5 class=\"cardright\"><span id=\"i-47\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Knight_of_Cups\">カップの騎士(Knight of Cups)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/c12.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">カップの騎士(Knight of Cups)</span><br />
　キーワード：ロマンス　接近　優美<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>戦いを好まず、優美で、「愛する者を守りたい」というロマンティストのようです。優しく繊細な面が強いです。男性から愛の告白やプロポーズを受けることが期待できます。優しくロマンティックな出来事が近づいています。</p>
<div class=\"akairo\">逆位置の意味</div><p>腹に一物あって、口先だけの詐欺師かもしれません。不純な理由から甘い言葉でたぶらかしたり、惑わされたりしないように注意が必要です。また恋愛関係にある二人であれば、相手が離れていくという暗示になっています。</p>
<h5 class=\"cardright\"><span id=\"i-48\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Queen_of_Cups\">カップの女王(Queen of Cups)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/c13.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">カップの女王(Queen of Cups)</span><br />
　キーワード：深い愛情　物思い　優柔不断<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>幻想や夢想の世界に浸っていて、現実的に対処することをせず、ただ状況を静観しています。すべてのクィーンの中で最も良妻賢母型です。女性的な深い愛情に包まれて幸福感を得られます。女としての魅力が高まります。</p>
<div class=\"akairo\">逆位置の意味</div><p>感情的だったり、ヒステリックな女性に振り回されて消耗するようです。気まぐれで自己中心的な言い分に辟易しそうです。不名誉な体験であったり、堕落的な方向へ傾くかもしれません。女性の悪い部分が目につきます。</p>
<h5 class=\"cardright\"><span id=\"i-49\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"King_of_Cups\">カップの王(King of Cups)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/c14.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">カップの王(King of Cups)</span><br />
　キーワード：深い人情　親切心　優柔不断<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>感情に振り回されないで、公平に判断を下すことができるし、責任感が感じられます。愛情豊かな親切な人物と縁がありそうです。影響力があり、頼りがいがあり、寛大です。厚い人情味があるので、判断には信頼がおけます。</p>
<div class=\"akairo\">逆位置の意味</div><p>平気で悪いことをしたり、口先から嘘を並べたりします。感情的になり、公平な理性を働かせることが難しいでしょう。節操に欠けて、異性関係の悪い噂が絶えません。節操なく不正や悪事を行うので、注意が必要な時です。</p>
<h5 class=\"cardright\"><span id=\"i-50\">タロットカード：小アルカナ</span></h5>

<h3><span id=\"Ace_of_Sword\">ソードのＡ(Ace of Sword)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/s1.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ソードのＡ(Ace of Sword)</span><br />
　キーワード：勝利　男性的理性　暴力<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>男性的な理性によって力づくで勝利をおさめることができます。金や権力を利用して異性や立場を得ることができます。名士や富豪同志の結婚という意味もあります。異性のお見合いや、会社の合併、乗っ取り、裁判所のこと。</p>
<div class=\"akairo\">逆位置の意味</div><p>激しい攻撃に遭って、思わぬ返り血を浴びて敗北します。企画が途中で挫折するかもしれません。暴力的で残忍な敗北にあい、挫折感しか残りません。論理的に考えようとしても無理があり、中途で挫折・敗北しそうです。</p>
<h5 class=\"cardright\"><span id=\"i-51\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Two_of_Swords\">ソードの２(Two of Swords)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/s2.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ソードの２(Two of Swords)</span><br />
　キーワード：バランス　調和　二者択一<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>かろうじてバランスを保てているようです。あやういところで平衡を保っている関係であり、内心は決められないことで緊張しています。恋愛というよりは友情に近い、さわやかな好意が強いようです。調和しているようです。</p>
<div class=\"akairo\">逆位置の意味</div><p>迷ってしまって決めることができません。いけない恋愛に心が傾き、家庭や仕事とのバランスが取れなくなってしまっています。浮気や不倫に走る可能性があります。はしたないマナーで失敗しないように気をつけましょう。</p>
<h5 class=\"cardright\"><span id=\"i-52\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Three_of_Swords\">ソードの３(Three of Swords)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/s3.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ソードの３(Three of Swords)</span><br />
　キーワード：心の痛み　三角関係　混乱<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>三角関係によって心に傷を負い、涙を流しているようです。複数の異性が関係している恋愛関係で誰かが心を痛めている、涙をとめることができません。その心の痛みは半端ではなく、離散や別居につながる恐れも高いです。</p>
<div class=\"akairo\">逆位置の意味</div><p>混乱して処理を誤るようです。損をする可能性もあります。複数の異性の関わる関係において、心が乱れて何がなんだかわからなくなって、「耐えられない」と感じるほどの激しい精神錯乱と心痛が襲ってきそうです。血の涙。</p>
<h5 class=\"cardright\"><span id=\"i-53\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Four_of_Swords\">ソードの４(Four of Swords)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/s4.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ソードの４(Four of Swords)</span><br />
　キーワード：休息　一時停止　入院<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>慌ただしく動いた後に小休止しているようです。忙しい時期であっても人間は一定時間休息をとらなければ、体調を崩して心身がまともに働きません。いったん問題をお預けにして、頭の中を空っぽにしてみるとよいでしょう。</p>
<div class=\"akairo\">逆位置の意味</div><p>ずっと停滞していた状態が変化して少しづつゆっくりと動き始めるようです。しっかりと準備をして用意周到に物事が再生されていくようです。「回復」というイメージもあります。再び慎重に動き出す時がやってきたのです。</p>
<h5 class=\"cardright\"><span id=\"i-54\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Five_of_Swords\">ソードの５(Five of Swords)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/s5.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ソードの５(Five of Swords)</span><br />
　キーワード：残酷　勝利　殺伐<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>残酷で非道な手段によって勝利をおさめることができます。勝てますが殺伐とした気分になるでしょう。それを避けるためには、もっと相手に対して思いやりや愛情のこもった言葉を使う必要があります。狡猾に相手を出し抜きます。</p>
<div class=\"akairo\">逆位置の意味</div><p>荒んだ環境やいじめ、人間関係のトラブルによって、屈辱感のある敗北を味わうことを余儀なくされるようです。パワーハラスメントや悪意のある人によって傷つけられるでしょう。あと埋葬とか葬儀という意味もあります。</p>
<h5 class=\"cardright\"><span id=\"i-55\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Six_of_Swords\">ソードの６(Six of Swords)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/s6.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ソードの６(Six of Swords)</span><br />
　キーワード：出発　進展　援護者<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>自分を見守ってくれる人と一緒に、新しい環境へ進むことができそうです。順調なスタートを切ることができます。穏やかに事が動き出すのを実感できるでしょう。擁護者や水先案内人、母親によって守られながら移動します。</p>
<div class=\"akairo\">逆位置の意味</div><p>出発が遅れそうです。あなたの思うように物事が進まない時です。相手が逃亡してしまったり、行き先が分からなくなったりしそうです。物事の進展が遅くて、出発は延期される可能性もあります。先行きに不安を抱えています。</p>
<h5 class=\"cardright\"><span id=\"i-56\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Seven_of_Swords\">ソードの７(Seven of Swords)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/s7.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ソードの７(Seven of Swords)</span><br />
　キーワード：狡猾　策略　アドバイス<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>ずるがしこい行動によって利益を得ることができそうです。腹の中に一物あって、その策略は計画通りに進んで、目的を狡猾なやり方で達成することができます。成功したとしてもそのやり方は正しくないかもしれません。</p>
<div class=\"akairo\">逆位置の意味</div><p>何か役にたつアドバイスや援助を受けることが可能です。専門家に相談をすることで問題解決の一歩を踏み出せます。仲間に入れてもらって、仲間と協力しあうことで、前進することができます。矛盾が解消できそうです。</p>
<h5 class=\"cardright\"><span id=\"i-57\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Eight_of_Swords\">ソードの８(Eight of Swords)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/s8.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ソードの８(Eight of Swords)</span><br />
　キーワード：身動きできない　自由がきかない　立ち往生<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>全く身動きがとれず、自由がきかず、拘束されている状態のようです。眠る時間もないほど仕事が忙しいのかもしれませんし、体調が悪くてずっと床に臥せているのかもしれません。何かの強迫観念に突き動かされているようです。</p>
<div class=\"akairo\">逆位置の意味</div><p>忍耐が求められる苦しい状況です。目の前に障害がそびえたっていて、それを超えて進むことができません。足踏み状態ですが、状況は次第に悪化していきそうです。想定外のハプニングやトラブルに巻き込まれがちな時です。</p>
<h5 class=\"cardright\"><span id=\"i-58\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Nine_of_Swords\">ソードの９(Nine of Swords)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/s9.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ソードの９(Nine of Swords)</span><br />
　キーワード：悲しみ　苦悩　疑心暗鬼<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>深い悲しみに襲われて、ベッドから這い出ることもできません。激しい精神的な苦悩が次から次へと襲い掛かってくるようです。長く続く悲しみ、死ぬまで脳裏からぬぐえない、一生を変えるような苦しみに見舞われます。</p>
<div class=\"akairo\">逆位置の意味</div><p>他者や環境を通じて、侮辱・噂話や誹謗中傷に悩まされて、傷ついたり、悲しんだり、うんざりしたりしがちな時です。疑いすぎて疑心暗鬼になっていたり、事実を悪くとらえすぎて、被害者意識にとらわれているようです。</p>
<h5 class=\"cardright\"><span id=\"i-59\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"10Ten_of_Swords\">ソードの10(Ten of Swords)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/s10.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ソードの10(Ten of Swords)</span><br />
　キーワード：終了　絶望　好転<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>自分が望まない形、内心怖れているような形で関係が終了します。絶望や死を暗示します。しかし腐れ縁や本来望まない関係、苦痛で仕方がなかった状態に、完全に終止符が打たれることで、新しい展開を暗示しています。</p>
<div class=\"akairo\">逆位置の意味</div><p>薄い希望の光が差し込んできます。絶望的だと思っていた状態・関係から少しづつ回復している・物事が改善していることを実感できる時です。ずっと続く好転・改善ではなく、一時的な改善・好転で終わる場合が多いです。</p>
<h5 class=\"cardright\"><span id=\"i-60\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Page_of_Swords\">ソードの王子(Page of Swords)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/s11.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ソードの王子(Page of Swords)</span><br />
　キーワード：悪意　敵意　警戒心<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>悪意や敵意があります。頭がよく警戒心が強く、用心深い性質です。かなりネガティブな感情が強いと読めますし、うまく付き合っていくには一筋縄の苦労では足りません。人を信用していませんし、いつでも攻撃に転じます。</p>
<div class=\"akairo\">逆位置の意味</div><p>とげとげしく、周囲に対して攻撃的・好戦的です。誹謗中傷や噂話によって他の足を引っ張ること・感情を傷つけることをあえて好みます。幼児の残酷さと、大人の常識を同時に兼ね備えた人物のようです。厳しい知らせが届きます。</p>
<h5 class=\"cardright\"><span id=\"i-61\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Knight_of_Swords\">ソードの騎士(Knight of Swords)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/s12.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ソードの騎士(Knight of Swords)</span><br />
　キーワード：突進　迅速　攻撃性<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>険しい道のりになるでしょうが、怖れを知らず突進していくことで道が開けそうです。全力を出して突進します。突進してくる状況・人物に驚かされるかもしれません。じっと静観しているだけでは済まない事態になるでしょう。</p>
<div class=\"akairo\">逆位置の意味</div><p>先を急ぎ過ぎて一方的になったり、攻撃性が悪い形で目立つようになったり、目標に執着するあまり周囲が全く見えないようです。急な方向転換によって姿を急にくらましたり、それまで突進していたのに一時停止したりします。</p>
<h5 class=\"cardright\"><span id=\"i-62\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Queen_of_Swords\">ソードの女王(Queen of Swords)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/s13.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ソードの女王(Queen of Swords)</span><br />
　キーワード：冷淡　知的　批判的<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>心を開くことができないので恋愛や人間関係が良い方向へ発展しにくいです。恋愛よりは仕事、異性に無関心なキャリアウーマンかもしれません。警戒心が強く、知的で、攻撃的な一面がある独身の女性が関わっています。</p>
<div class=\"akairo\">逆位置の意味</div><p>批判的な言葉を口に出すことで人を傷つけます。ネガティブに相手を観察していたり、過去にいつまでも拘泥して根に持ったり、批判的になって相手を攻撃します。他人を平気で切り捨て傷つけるので周囲から好かれません。</p>
<h5 class=\"cardright\"><span id=\"i-63\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"King_of_Swords\">ソードの王(King of Swords)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/s14.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ソードの王(King of Swords)</span><br />
　キーワード：知性的　権力　残虐<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>頭のよいクールな男性が関わっているようです。権力のある、立場の高い人物がその力を使う・指導者的な立場をとります。頭脳優先なので人間的な温かみや共感性には欠けるようです。ドライな態度が原因で誤解されます。</p>
<div class=\"akairo\">逆位置の意味</div><p>同情心や人間的な優しさのまったく感じられない冷たさ・残酷さが問題に影響しています。冷たさや残酷さによって他人の恨みを買うのに注意するべきです。エゴイスティックで、他人が苦しむのを見ても何も感じません。</p>
<h5 class=\"cardright\"><span id=\"i-64\">タロットカード：小アルカナ</span></h5>

<h3><span id=\"Ace_of_Pentacle\">ペンタクルのＡ(Ace of Pentacle)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/p1.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ペンタクルのＡ(Ace of Pentacle)</span><br />
　キーワード：入手　手応え　幸運<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>手応えが感じられます。損得勘定をして望んだ結果がえられそうです。何かとても貴重なもの、高価なもの、あなたにとって価値のある関係などが良い方向へ向かっていることを実感できるでしょう。相当な幸運が訪れます。</p>
<div class=\"akairo\">逆位置の意味</div><p>ゴールは近いので、ここで諦めるのは損失です。恋愛関係において、あと一歩で手応えが感じられるようです。完成間近です。価値のある物質・金銭・関係を手に入れるためには、あと少しの地点まで到達しているようです。</p>
<h5 class=\"cardright\"><span id=\"i-65\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Two_of_Pentacles\">ペンタクルの２(Two of Pentacles)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/p2.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ペンタクルの２(Two of Pentacles)</span><br />
　キーワード：気分転換　楽しい　軽い<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>レジャーとか気分転換となる楽しい時間が期待できそうです。遊びに興じていて、愉しむ気持ちが強いです。あくまで「軽い」楽しさですから、真剣さには欠けています。時間つぶしにぴったりの気軽な遊び、交友関係などのことです。</p>
<div class=\"akairo\">逆位置の意味</div><p>無責任で刹那的な快楽に身を費やすようです。誘惑されても、軽く引き受けて後先を考えていません。軽薄で無責任で一時的な関係に終わりそうです。レジャーや会合などで明るさを強いられて無理に騒いでいるようです。</p>
<h5 class=\"cardright\"><span id=\"i-66\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Three_of_Pentacles\">ペンタクルの３(Three of Pentacles)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/p3.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ペンタクルの３(Three of Pentacles)</span><br />
　キーワード：熟練　向上　謙遜<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>向上を目指してこつこつと専門的な技能や腕前を磨くことに腐心しているようです。専門的な職務についているようです、あるいはそういう特殊な熟練を要求される立場にあります。連携しています、つねに努力を怠りません。</p>
<div class=\"akairo\">逆位置の意味</div><p>無責任で刹那的な快楽に身を費やすようです。誘惑されても、軽く引き受けて後先を考えていません。軽薄で無責任で一時的な関係に終わりそうです。レジャーや会合などで明るさを強いられて無理に騒いでいるようです。</p>
<h5 class=\"cardright\"><span id=\"i-67\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Four_of_Pentacles\">ペンタクルの４(Four of Pentacles)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/p4.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ペンタクルの４(Four of Pentacles)</span><br />
　キーワード：執着　強欲　保身<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>お金や持ち物、異性、人間関係などに執着をしています。保身一辺倒になってしまって、現状を何としても変えたくないと意固地になっているので状況が変化しにくいです。安心のために現状に固執していて誰にも分け与えません。</p>
<div class=\"akairo\">逆位置の意味</div><p>強欲さから金銭や物質、人間関係に執着をして、さらにもっとよこせと欲している状態です。執着心を捨てることで状況に新しい変化を起こすことができます。保守的な気持ちが強いので現状の安定感を壊すことを怖れます。</p>
<h5 class=\"cardright\"><span id=\"i-68\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Five_of_Pentacles\">ペンタクルの５(Five of Pentacles)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/p5.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ペンタクルの５(Five of Pentacles)</span><br />
　キーワード：貧困　空疎　混乱<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>経済的に困窮状態にあるようです。物質・金銭だけではなく、人間関係や愛情やすべての面においてひどい不足が生じていて、恵まれていない状態にあると言えます。愛情がスカスカになっていて、まったく心が通いあいません。</p>
<div class=\"akairo\">逆位置の意味</div><p>貧窮状態であることから混乱が生じて、先行きをまったく読むことができません。心理的な動揺や混乱であることもあります。相手の気持ちが理解できず、実際以上に精神的に動揺・混乱していて破たんをきたしているようです。</p>
<h5 class=\"cardright\"><span id=\"i-69\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Six_of_Pentacles\">ペンタクルの６(Six of Pentacles)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/p6.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ペンタクルの６(Six of Pentacles)</span><br />
　キーワード：慈愛　親切　思いやり<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>心からの思いやりや優しさを注がれる、もしくは自分が注いでいます。誰かから誰かに見返りを要求しない、愛情を注がれ温かい気持ちになります。相手に対して、心から思いやること、優しくすることが状況を好転させます。</p>
<div class=\"akairo\">逆位置の意味</div><p>出し惜しみするようです。自己中心的でケチに走ります。物質や金銭だけではなく、気持ちや優しさといった精神的な何かであっても、もったいないので出し惜しみをして、情をかけるなど無駄なことだ…と切り捨てます。</p>
<h5 class=\"cardright\"><span id=\"i-70\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Seven_of_Pentacles\">ペンタクルの７(Seven of Pentacles)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/p7.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ペンタクルの７(Seven of Pentacles)</span><br />
　キーワード：成長　見守る　不満<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>不満を内心感じながらも、物事の変化や成長を見守っているようです。物事・関係はゆっくり成長していきます。きつい仕事や仕事に対する不満という意味もあります。今は行動を起こさずじっと観察したほうがよい時期です。</p>
<div class=\"akairo\">逆位置の意味</div><p>仕事に関する不満や悩みがいよいよ現実化します。資金操りがうまくいかず、思ったよりも稼げない仕事であることが明白になるでしょう。変化が感じられず、退屈を持て余し、怠惰になり、転職ばかり考えるようになります。</p>
<h5 class=\"cardright\"><span id=\"i-71\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Eight_of_Pentacles\">ペンタクルの８(Eight of Pentacles)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/p8.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ペンタクルの８(Eight of Pentacles)</span><br />
　キーワード：勤勉　誠実　惰性<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>誠実で勤勉な、安心感を与える人柄です。一気に成功したい、上昇したいというのなら無理ですが、毎日コツコツと他人が見ている所でも見ていない所でも、自分なりの努力を続けることができます。職人の仕事という意味もあります。</p>
<div class=\"akairo\">逆位置の意味</div><p>怠け心が仇になって努力ができず、成長できません。自分の力を過信しています。同じことの繰り返しが続き、内心うんざりしています。お金のためだけに我慢を続けているようです。惰性でやっている仕事・関係という意味。</p>
<h5 class=\"cardright\"><span id=\"i-72\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Nine_of_Pentacles\">ペンタクルの９(Nine of Pentacles)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/p9.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ペンタクルの９(Nine of Pentacles)</span><br />
　キーワード：美しさ　若さ　愛人<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>美しさや色気を武器にして、成功・金銭・愛情を手にいれることができます。美しい女性の武器をふんだんに利用します。実力者から愛されることで開運できます。女性としてもっと魅力的になれる時です。愛人の意味も。</p>
<div class=\"akairo\">逆位置の意味</div><p>若さや女性の肉体を悪用して、みだらな関係に陥ります。不倫や愛人関係という意味もあります。金銭が目的で異性を誘惑しがちです。自己中心的になり、計算高さが際立ち、世間的に好ましくないことに足を踏み入れます。</p>
<h5 class=\"cardright\"><span id=\"i-73\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"10Ten_of_Pentacles\">ペンタクルの10(Ten of Pentacles)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/p10.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ペンタクルの10(Ten of Pentacles)</span><br />
　キーワード：家族　和やか　ルーズ<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>どんなことが起こっても家族のような温かい絆によってサポートされているので悪い結果にはならないでしょう。家族のような和やかで和気藹々とした雰囲気でほっと心が癒されそうです。血がつながっているという意味も。</p>
<div class=\"akairo\">逆位置の意味</div><p>だらしがなく、何ともしまらない、ルーズな雰囲気になります。気が抜けているので、規律を守ったり、モチベーションを維持したりすることが難しくなりそうです。家族の間で喧嘩やトラブルが起こる可能性があります。</p>
<h5 class=\"cardright\"><span id=\"i-74\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Page_of_Pentacles\">ペンタクルの王子(Page of Pentacles)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/p11.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ペンタクルの王子(Page of Pentacles)</span><br />
　キーワード：勤勉　堅実　安定<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>堅実であり、信頼のおける、真面目な人物・関係です。常に向上・拡大することを目標として、真面目に将来を考えて、コツコツ貯金するように働き続けています。勤勉になって、安定することを第一の目標に据えています。</p>
<div class=\"akairo\">逆位置の意味</div><p>金銭や仕事に関して悪い知らせが届きそうです。怠け者になり、すぐにさぼることばかり考えるので向上しません。目標があってもそれに向けて努力や節制ができないのですぐに投げ出してしまいそうです。学問よりも商売。</p>
<h5 class=\"cardright\"><span id=\"i-75\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Knight_of_Pentacles\">ペンタクルの騎士(Knight of Pentacles)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/p12.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ペンタクルの騎士(Knight of Pentacles)</span><br />
　キーワード：着実　安定　マンネリ<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>ゆっくりと着実に前進し続けることができて、安定しています。安定を志向していて、あまり目立つことを好みません。どちらかというと保守的で、堅実な考え方をします。刺激は無いけれども確実な選択肢になるでしょう。</p>
<div class=\"akairo\">逆位置の意味</div><p>マンネリに悩まされそうです。これといった不満や喧嘩はありませんが、毎日に新鮮さやスピード感がありません。現状を変えようというエネルギーも自分の中から枯渇してしまったようです。停滞を余儀なくされるでしょう。</p>
<h5 class=\"cardright\"><span id=\"i-76\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"Queen_of_Pentacles\">ペンタクルの女王(Queen of Pentacles)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/p13.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ペンタクルの女王(Queen of Pentacles)</span><br />
　キーワード：保守的　裕福　合理的<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>穏やかで保守的な良妻賢母型の女性が問題に関わっているようです。金銭感覚の発達した女性という意味もあります。精神的な励ましよりも、資金援助のような、現物支給による支援が期待できそうです。状況が安定します。</p>
<div class=\"akairo\">逆位置の意味</div><p>生活の中に動きや刺激がなく、退屈で、無駄遣いに走りそうです。穏やかというよりは、鈍いつまらない自立していない女性のようです。洗練されていないので野暮ったく、世間知らずで、話していてあまり面白くありません。</p>
<h5 class=\"cardright\"><span id=\"i-77\">タロットカード：小アルカナ</span></h5>
<h3><span id=\"King_of_Pentacles\">ペンタクルの王(King of Pentacles)</span></h3>
<p><img src=\"https://meigen.keiziban-jp.com/manabi/img/tarotto/p14.jpg\" alt=\"\" align=\"left\"/><br />
　<span class=\"zinzyanamae\">ペンタクルの王(King of Pentacles)</span><br />
　キーワード：経済力　地位　損得勘定<br />
　　<br />
</p>
<div class=\"clear\"></div><div class=\"akairo\">正位置の意味</div><p>経済力があって、社会的な地位の高い男性から支援を受けて物事が安定します。経済的に成功することで状況が改善します。人間的な情の深さよりも、お金とか実際的な支援を好む傾向があります。社会的な地位が向上するときです。</p>
<div class=\"akairo\">逆位置の意味</div><p>独占欲の強さに悩まされそうです。強欲さや唯物主義によって、人間的な共感や思いやりは完全に後回しになってしまいます。年配の男性で金銭に執着心が強いようです。損得勘定が目立ち、メリットのない人間には冷たいです。<br />
<div class=\"boxsContainer\">
<div class=\"addd\">
<ins class=\"adsbygoogle\"
style=\"display:inline-block;width:300px;height:250px\"
data-ad-client=\"ca-pub-1941825262285936\"
data-ad-slot=\"2641798800\"></ins>
<script>(adsbygoogle=window.adsbygoogle||[]).push({});</script></div><div class=\"addd2\">

<ins class=\"adsbygoogle\"
style=\"display:inline-block;width:300px;height:250px\"
data-ad-client=\"ca-pub-1941825262285936\"
data-ad-slot=\"2641798800\"></ins>
<script>(adsbygoogle=window.adsbygoogle||[]).push({});</script></div></div></p></div><div id=\"wpcr_respond_1\">

        ";


        $ex_content = explode("\n", $content);

        $str = "";
        foreach ($ex_content as $v) {
            $str .= trim($v);
        }

        $ex_str = explode("|", strtr($str, ['><' => '>|<']));

        $a = [];
        $z = 0;
        foreach ($ex_str as $k => $v) {
            if (preg_match("/<h3>/", trim($v))) {
                $a[] = $k;
            }

            if (preg_match("/wpcr_respond_1/", trim($v))) {
                $z = $k;
            }
        }

        $a[] = $z;

//        print_r($a);

        $b = [];
        for ($i = 0; $i < count($a) - 1; $i++) {
            $str2 = "";
            for ($j = $a[$i]; $j < $a[$i + 1]; $j++) {
                $str2 .= trim($ex_str[$j]);
            }
            $b[] = $str2;
        }

//print_r($b);

        $c = [];
        foreach ($b as $k => $v) {
            $ex_v = explode("|", strtr(trim($v), ['><' => '>|<']));
            foreach ($ex_v as $v2) {
                if (preg_match("/<img/", trim($v2))) {
                    preg_match("/tarotto\/(.+)\.jpg/", trim($v2), $m);

                    $key = "";
                    if (preg_match("/w(.+)/", trim($m[1]), $m2)) {
                        $key = "wands" . sprintf("%02d", trim($m2[1]));
                    } elseif (preg_match("/c(.+)/", trim($m[1]), $m2)) {
                        $key = "cups" . sprintf("%02d", trim($m2[1]));
                    } elseif (preg_match("/s(.+)/", trim($m[1]), $m2)) {
                        $key = "swords" . sprintf("%02d", trim($m2[1]));
                    } elseif (preg_match("/p(.+)/", trim($m[1]), $m2)) {
                        $key = "pentacles" . sprintf("%02d", trim($m2[1]));
                    } else {
                        $key = "big" . sprintf("%02d", trim($m[1]));
                    }

                    $c[$k]['key'] = $key;
                }
                if (preg_match("/<p/", trim($v2))) {
                    $c[$k][] = trim(strip_tags($v2));
                }
            }
        }
        print_r($c);

        foreach ($c as $v) {
            $update = [];
            $update["msg_just3"] = trim($v[1]);
            $update["msg_reverse3"] = trim($v[2]);

            \DB::table("t_tarot")->where("image", "=", $v['key'])->update($update);
        }

    }
}

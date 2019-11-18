<?php
/**
 * Created by PhpStorm.
 * User: hiliq
 * Date: 2019/2/26
 * Time: 13:28
 */

namespace app\ucenter\controller;


use think\App;
use think\Controller;
use think\facade\Session;
use think\facade\View;

class BaseUcenter extends Controller
{
    protected $tpl;
    protected $uid;
    protected $prefix;
    protected $redis_prefix;

    protected function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->uid = session('xwx_user_id');
        if (is_null($this->uid)){
            $this->redirect(url('/login'));
        }

        $vip_expire_time = session('vip_expire_time');
        if (!empty($vip_expire_time)){
            if($vip_expire_time - time() <= 0){ //计算出会员是否过期
                session('xwx_vip_expire_time', null);
            }
        }
    }

    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->redis_prefix = config('cache.prefix');
        $this->prefix = config('database.prefix');
        $tpl_root = './template/default/ucenter/';
        $controller = strtolower($this->request->controller());
        $action = strtolower($this->request->action());
        if ($this->request->isMobile()){
            $this->tpl = $tpl_root.$controller.'/'.$action.'.html';
        }else{
            $this->tpl = $tpl_root.$controller.'/'.'pc_'.$action.'.html';
        }


        View::share([
            'url' => config('site.url'),
            'site_name' => config('site.site_name'),
            'img_site' => config('site.img_site'),
            'book_ctrl' => BOOKCTRL,
            'chapter_ctrl' => CHAPTERCTRL,
            'tag_ctrl' => TAGCTRL,
            'booklist_act' => BOOKLISTACT,
            'search_ctrl' => SEARCHCTRL,
            'rank_ctrl' => RANKCTRL,
            'update_act' => UPDATEACT,
            'author_ctrl' => AUTHORCTRL
        ]);
    }
}
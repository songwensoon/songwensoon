<?php
/**
 * This source file is part of GotCms.
 *
 * GotCms is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * GotCms is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License along
 * with GotCms. If not, see <http://www.gnu.org/licenses/lgpl-3.0.html>.
 *
 * PHP Version >=5.3
 *
 * @category    Gc
 * @package     Application
 * @subpackage  Design
 * @author      Pierre Rambaud (GoT) <pierre.rambaud86@gmail.com>
 * @license     GNU/LGPL http://www.gnu.org/licenses/lgpl-3.0.html
 * @link        http://www.got-cms.com
 */
 /** @var $this \Zend\View\Renderer\PhpRenderer */
if($this->admin())
{
    $permissions = $this->admin()->getRole()->getUserPermissions();
}
$navigationData = $this->navigationData;
if(!empty($navigationData))
{
	$menu = $navigationData['menu'];
	$navigation = $navigationData['navigation'];
	$navigation_url = $navigationData['navigation_url'];
}
?>
<div class="header_bg">
    <div class="logo lf"><a href="<?php echo $this->url('admin'); ?>" target="_blank"><span class="invisible"><?php echo $this->escapeHtml($this->translate('SoonCms Admin')); ?></span></a></div>
	<div class="col-auto" style="overflow: visible">
		<ul class="nav white" id="top_menu">
			<li id="_M1000" class="on top_menu"><a href="javascript:_M(1000,'index.php?M=admin_right')"  onclick="check_web('1000');" hidefocus="true" style="outline:none;">管理首页</a></li>
			<?php if(!empty($navigation)){
					foreach($navigation as $menu){
				?>
			<li id="_M<?php echo $menu['id'];?>" class="top_menu"><a class="<?php echo $menu['classname'];?>" href="javascript:_M(<?php echo $menu['id'];?>,'<?php echo $navigation_url[$menu['id']];?>')" onclick="check_web(<?php echo $menu['id'];?>);" hidefocus="true" style="outline:none;"><?php echo $menu['name'];?></a></li>
			<?php }}?>
		</ul>
	</div>
	<div>
		<div class="admin-header_top"> 
			<div class="admin-header_top_wz">
				<a href="/" target="_blank"><?php echo $this->escapeHtml($this->translate('SoonCms Web')); ?></a> | <a href="http://www.phpyun.com" target="_blank"><font color="#FF0000">授权</font></a> | <a href="http://bbs.phpyun.com" target="_blank">论坛</a> | <a href="javascript:art.dialog({id:'map',iframe:'index.php?C=map', title:'后台地图', width:'700', height:'500', lock:true});void(0);"><span>后台地图</span></a> | <a href="index.php?M=index&C=del_cache" target="right"><span>更新缓存</span></a> | <a href="http://bbs.phpyun.com/thread.php?fid=11" target="_blank">帮助？</a>
			</div>
			<a href="{yun:}$config.sy_weburl{/yun}" target="_blank" id="site_homepage"><img src="/backend/images/top1.png"></a> <a href="index.php?M=index&C=del_cache" target="right"><img src="/backend/images/top2.png"></a> <a href="<?php echo $this->url('config/user/logout'); ?>"><img src="/backend/images/top3.png"></a> 
		</div>
    </div>
</div>


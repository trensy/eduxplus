<?php

namespace App\DataFixtures;

use App\Bundle\AdminBundle\Service\Jw\TeacherService;
use App\Bundle\AdminBundle\Service\Mall\CouponService;
use App\Bundle\AdminBundle\Service\Mall\GoodsService;
use App\Bundle\AdminBundle\Service\Mall\OrderService;
use App\Bundle\AdminBundle\Service\Mall\PayService;
use App\Bundle\AdminBundle\Service\Teach\ChapterService;
use App\Bundle\AdminBundle\Service\Teach\CourseService;
use App\Bundle\AdminBundle\Service\Teach\ProductService;
use App\Bundle\AdminBundle\Service\Teach\StudyPlanService;
use App\Bundle\AppBundle\Lib\Service\HelperService;
use App\Entity\BaseMenu;
use App\Entity\BaseOption;
use App\Entity\BaseRole;
use App\Entity\BaseRoleMenu;
use App\Entity\BaseRoleUser;
use App\Entity\BaseUser;
use App\Entity\JwSchool;
use App\Entity\TeachAgreement;
use App\Entity\TeachCategory;
use App\Entity\TeachProducts;
use Doctrine\Bundle\FixturesBundle\Fixture;
//use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class InitDataFixtures extends Fixture
{
    protected $passwordEncoder;
    /**
     * @var ObjectManager
     */
    protected $manager;
    protected $helperService;
    protected $courseService;
    protected $chapterService;
    protected $teacherService;
    protected $productService;
    protected $studyPlanService;
    protected $goodsService;
    protected $couponService;
    protected $orderService;
    protected $payService;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        HelperService $helperService,
        CourseService $courseService,
        ChapterService $chapterService,
        TeacherService $teacherService,
        ProductService $productService,
        StudyPlanService $studyPlanService,
        GoodsService $goodsService,
        CouponService $couponService,
        OrderService $orderService,
        PayService $payService
    )
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->helperService = $helperService;
        $this->courseService = $courseService;
        $this->chapterService = $chapterService;
        $this->teacherService = $teacherService;
        $this->productService = $productService;
        $this->studyPlanService = $studyPlanService;
        $this->goodsService = $goodsService;
        $this->couponService = $couponService;
        $this->orderService = $orderService;
        $this->payService = $payService;
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        //添加分类
        $pid = $this->addCateGory("计算机", 1, 0, 0, "");
        $this->addCateGory("前言技术", 1, 0, $pid, ",{$pid},");
        $cid1 = $this->addCateGory("程序设计与开发", 1, 0, $pid, ",{$pid},");
        $this->addCateGory("计算机基础与应用", 1, 0, $pid, ",{$pid},");
        $this->addCateGory("软件工程", 1, 0, $pid, ",{$pid},");
        $this->addCateGory("网络与安全技术", 1, 0, $pid, ",{$pid},");
        $this->addCateGory("硬件系统及原理", 1, 0, $pid, ",{$pid},");
        $this->addCateGory("c语言不挂科", 1, 0, $pid, ",{$pid},");
        $this->addCateGory("2020考研计算机", 1, 0, $pid, ",{$pid},");
        $pid = $this->addCateGory("外语", 1, 0, 0, "");
        $this->addCateGory("听力/口语", 1, 0, $pid, ",{$pid},");
        $this->addCateGory("语法/阅读", 1, 0, $pid, ",{$pid},");
        $this->addCateGory("协作/翻译", 1, 0, $pid, ",{$pid},");
        $this->addCateGory("文学与语言学", 1, 0, $pid, ",{$pid},");
        //添加协议
        $agreeId = $this->addAgreement('用户协议','欢迎您来到多学课堂。请您仔细阅读以下条款，如果您对本协议的任何条款表示异议，您可以选择不进入多学课堂。当您注册成功，无论是进入多学课堂，还是在多学课堂上发布任何内容（即“内容”），以及直接或通过各类方式（如站外API引用等）间接使用多学课堂服务和数据的行为，均意味着您（即“用户”）完全接受本协议项下的全部条款。 若您对本声明的任何条款有异议，请停止使用多学课堂所提供的全部服务。', 1);
        $this->addAgreement('隐私政策','保护用户隐私是多学课堂的基本政策，非经用户许可，多学课堂保证不对外公开或向第三方提供单个用户注册资料及用户在使用网络服务时存储在多学课堂的非公开内容，但下列情况除外：
(1)遵守有关法律、法规的规定，包括在国家有关机关查询时，提供用户注册信息、用户在多学课堂的网页上发布的信息内容及其发布时间、互联网地址或者域名。
(2)保持维护多学课堂知识产权和其他重要权利。
(3)在紧急情况下竭力维护用户个人和社会大众的隐私安全。
(4)根据本协议相关规定或者多学课堂认为必要的其他情况下。
您使用或继续使用我们的服务，即意味着同意我们按照本《隐私政策》收集、使用、储存和分享您的相关信息。', 1);
        //校区
        $this->addSchool("同济大学北校区","上海市","上海城区", "杨浦区", "(021)66052500","国康路47号同济大学北校区", '<p><iframe style="width: 560px; height: 362px;" src="http://www.eduxplus.test/assets/plugins/tinymce/plugins/bdmap/bd.html?center=121.50707705070504%2C31.291328353729664&amp;zoom=14&amp;width=558&amp;height=360" frameborder="0"><span id="mce_marker" data-mce-type="bookmark">﻿​</span></iframe></p>');
        $schoolId1 = $this->addSchool("上海大学(宝山校区)","上海市","上海城区", "宝山区", "(021)66132222","上海市宝山区上大路99号", '<p><iframe style="width: 560px; height: 362px;" src="http://www.eduxplus.test/assets/plugins/tinymce/plugins/bdmap/bd.html?center=121.39903048091482%2C31.32144004759091&amp;zoom=14&amp;width=558&amp;height=360" frameborder="0"><span id="mce_marker" data-mce-type="bookmark">﻿​</span></iframe></p>');
        $teacherId1 = $this->teacherService->add("刘德华", "老牌实力明星", 1, $schoolId1, 1);

        $courseId = $this->courseService->add(1,"JAVA编程思想", 1, "", "JAVA编程思想", $cid1, $schoolId1, 33);
        $chapterId = $this->chapterService->add("JAVA基础", [$teacherId1], 0, time(), 1, 0, 0, $courseId);
        $this->chapterService->add("变量", [$teacherId1], $chapterId, time(), 1, 0, 0, $courseId);
        $this->chapterService->add("函数", [$teacherId1], $chapterId, time(), 1, 0, 1, $courseId);
        $this->chapterService->add("类", [$teacherId1], $chapterId, time(), 1, 0, 2, $courseId);
        $courseId1 = $this->courseService->add(1,"Golang编程思想", 1, "", "Golang编程思想", $cid1, $schoolId1, 33);
        $chapterId = $this->chapterService->add("Golang基础", [$teacherId1], 0, time(), 1, 0, 0, $courseId1);
        $this->chapterService->add("变量", [$teacherId1], $chapterId, time(), 1, 0, 0, $courseId1);
        $this->chapterService->add("函数", [$teacherId1], $chapterId, time(), 1, 0, 1, $courseId1);
        $this->chapterService->add("类", [$teacherId1], $chapterId, time(), 1, 0, 2, $courseId1);
        $productId = $this->productService->add(1,"高级编程", $agreeId, 1,22, $cid1, "高级编程");
        $this->studyPlanService->add($productId, "5.1学习计划", 1, 1, "2021-01-12 11:21:00",[$courseId, $courseId1],1,"5.1学习计划");
        //添加商品
        $goodImg = \GuzzleHttp\json_encode(["/assets/images/bigSmallFace.jpg"]);
        $smallImg = \GuzzleHttp\json_encode(["/assets/images/smallMallFace.jpg"]);
        $goods1 = $this->goodsService->add(1, "高级编程就业班", $productId, 0, $cid1, "保证就业", 1, [1], 160, 20, "6000.00", "5000.00", "2134",$goodImg,$smallImg, 1,0,$agreeId, 0, "保证就业");
        //添加优惠券
        $couponId = $this->couponService->add(1,"程序设计3折",2,30,100,time(), (time()+10*3600), 1, $cid1, 7,[$goods1], "程序设计语言打3折");
        $this->couponService->createCoupon($couponId);
        $couponSn = $this->couponService->sendCoupon(1, $couponId);
        $this->couponService->useCoupon(1, $couponSn);
        //添加订单
        $orderNo = $this->orderService->add(1, "高级编程就业班", $goods1, [], 320, 20, $couponSn, 2, "test", "测试");
        //添加支付
        $this->payService->add(1, 1, 320, $orderNo);
        $this->payService->completedPay($orderNo);
        $this->orderService->setOrderStatus($orderNo, 2);

    }


    protected function addSchool($name,$state,$city, $region, $linkin,$address, $descr){
        $model = new JwSchool();
        $model->setName($name);
        $model->setState($state);
        $model->setCity($city);
        $model->setRegion($region);
        $model->setLinkin($linkin);
        $model->setAddress($address);
        $model->setDescr($descr);
        $this->manager->persist($model);
        $this->manager->flush();
        return $model->getId();
    }

    protected function addAgreement($name,$isShow,$content){
        $model = new TeachAgreement();
        $model->setName($name);
        $model->setIsShow($isShow);
        $model->setContent($content);
        $this->manager->persist($model);
        $this->manager->flush();
        return $model->getId();
    }

    protected function addCateGory($name, $isShow, $sort, $pid, $path){
        $model = new TeachCategory();
        $model->setFindPath($path);
        $model->setParentId($pid);
        $model->setSort($sort);
        $model->setIsShow($isShow);
        $model->setName($name);
        $this->manager->persist($model);
        $this->manager->flush();
        return $model->getId();
    }


}

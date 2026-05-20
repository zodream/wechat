<?php
declare(strict_types=1);
namespace Zodream\ThirdParty\WeChat;
/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/8/20
 * Time: 0:01
 */
class PersonalMenuItem extends MenuItem {

    const IOS = 1;
    const Android = 2;
    const Others = 3;

    const MALE = 1;
    const FEMALE = 2;

    protected mixed $groupId;
    protected int $sex;
    protected int $os;
    protected mixed $country;
    protected mixed $province;
    protected mixed $city;
    protected mixed $language;

    public function setGroupId(mixed $arg) {
        $this->groupId = $arg;
        return $this;
    }

    public function setSex(int $arg) {
        $this->sex = $arg;
        return $this;
    }

    public function setOs(int $arg) {
        $this->os = $arg;
        return $this;
    }

    public function setCountry(mixed $arg) {
        $this->country = $arg;
        return $this;
    }

    public function setProvince(mixed $arg) {
        $this->province = $arg;
        return $this;
    }

    public function setCity(mixed $arg) {
        $this->city = $arg;
        return $this;
    }

    public function setLanguage(mixed $arg) {
        $this->language = $arg;
        return $this;
    }

    public function toArray() {
        $data = parent::toArray();
        if (!array_key_exists('button', $data)) {
            return $data;
        }
        $data['matchrule'] = [];
        if (!empty($this->groupId)) {
            $data['matchrule']['group_id'] = $this->groupId;
        }
        if (!empty($this->sex)) {
            $data['matchrule']['sex'] = $this->sex;
        }
        if (!empty($this->os)) {
            $data['matchrule']['client_platform_type'] = $this->os;
        }
        if (!empty($this->country)) {
            $data['matchrule']['country'] = $this->country;
        }
        if (!empty($this->province)) {
            $data['matchrule']['province'] = $this->province;
        }
        if (!empty($this->city)) {
            $data['matchrule']['city'] = $this->city;
        }
        if (!empty($this->language)) {
            $data['matchrule']['language'] = $this->language;
        }
        if (empty($data['matchrule'])) {
            unset($data['matchrule']);
        }
        return $data;
    }
}
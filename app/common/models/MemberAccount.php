<?php

namespace XApi\Models;

class MemberAccount extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $account_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $member_id;

    /**
     *
     * @var string
     * @Column(type="string", length=64, nullable=true)
     */
    public $account;

    /**
     *
     * @var string
     * @Column(type="string", nullable=true)
     */
    public $oauth_name;

    /**
     *
     * @var string
     * @Column(type="string", length=512, nullable=true)
     */
    public $account_token;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $expire_time;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $created_at;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=true)
     */
    public $modified_at;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("xapi");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'member_accounts';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return MemberAccount[]|MemberAccount
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return MemberAccount
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}

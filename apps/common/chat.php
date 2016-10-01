<?php

namespace common;


class chat
{
    const SINGLE_CHAT   = 1;
    const GROUP_CHAT    = 2;
    const JOIN_FRIEND   = 3;
    const JOIN_SWARM    = 4;
    const REMOVE_FRIEND = 5;
    const QUIT_SWARM    = 6;
    const LOGIN_OUT     = 7;
    const REMOVE_GROUP  = 8;
    const ADD_GROUP     = 9;
    const SEND_CHECK_MSG = 10;
    const CREATE_SWARM   = 11;
    const DELETE_SWARM   = 12;
    const REMOVE_FRIEND_FOR_SWARM = 13;
    const ADD_FRIEND_MSG_TYPE = 1;
    const REMOVED_SWARM_MSG_TYPE = 2;
    const JOIN_SWARM_MSG_TYPE = 3;
    const JOIN_SWARM_SUCCESS  = 14;
    const DEFAULT_GROUP_NAME = "我的好友";
    const ON_LINE = 'online';
    const OFF_LINE = 'hide';
    const CHANGE_LINE = 15;
} 
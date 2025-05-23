<?php

namespace App\Http\Helpers;

class Constants
{

    /**
     * OTP statuses
     */

    const RESET_PASSWORD_OTP_CREATED = 1;
    const RESET_PASSWORD_OTP_ACTIVATED = 2;
    const RESET_PASSWORD_OTP_EXPIRED = 3;

    /**
     * OTP lifetime
     * In hours
     */

    const OTP_LIFETIME = 1;

    /**
     * OTP verification statuses
     */

    const RESET_PASSWORD_OTP_VERIFICATION_CREATED = 1;
    const RESET_PASSWORD_OTP_VERIFICATION_ACTIVATED = 2;
    const RESET_PASSWORD_OTP_VERIFICATION_EXPIRED = 3;

    /**
     * OTP verification token lifetime
     * In hours
     */

    const OTP_VERIFICATION_TOKEN_LIFETIME = 1;


    /**
     * HTTP statuses
     */

    const HTTP_SUCCESS = 200;
    const HTTP_ERROR = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_NOT_FOUND = 404;
    const HTTP_FORBIDDEN = 403;

    /**
     * Prefixes
     */

    const PREFIX_USER_IMAGE_PROFILE = "user.image.profile.";

    /**
     * Directories
     */

    const DIR_USER_IMAGES = "/images/user/";
    const DIR_VIDEO_DOWNLOADS = "/video_downloads/";

    /**
     * Images
     */

    const IMG_DEFAULT_USER = "default.jpg";

    /**
     * Videos
     */

    const VIDEO_JOB_STATUS_SUCCESS = 1;
    const VIDEO_JOB_STATUS_ERROR = 2;
}

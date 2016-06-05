<?php
/**
 * @Author: Leandro Henrique Reis <emtudo@gmail.com>
 * @Date:   2016-05-05 08:41:00
 * @Last Modified by:   Leandro Henrique Reis
 * @Last Modified time: 2016-06-04 19:51:53
 */

namespace Domain\Lesson;

use Domain\Repositories\BaseRepository;

class LessonRepository extends BaseRepository
{
    public function model()
    {
        return Lesson::class;
    }
}

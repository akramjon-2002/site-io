<?php

use yii\db\Migration;

/**
 * Initializes RBAC roles and permissions.
 */
class m240315_120000_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // Create a rule: AuthorRule
        // This rule will be used to check if the current user is the author of a post.
        $authorRule = new \yii\rbac\Rule([
            'name' => 'isAuthor',
            'execute' => function ($user, $item, $params) {
                return isset($params['post']) ? $params['post']->user_id == $user : false;
            }
        ]);
        $auth->add($authorRule);

        // --- Permissions ---
        // Permission to create a post
        $createPost = $auth->createPermission('createPost');
        $createPost->description = 'Create a post';
        $auth->add($createPost);

        // Permission to update any post (typically for admin)
        $updatePost = $auth->createPermission('updatePost');
        $updatePost->description = 'Update any post';
        $auth->add($updatePost);
        
        // Permission to update own post
        $updateOwnPost = $auth->createPermission('updateOwnPost');
        $updateOwnPost->description = 'Update own post';
        $updateOwnPost->ruleName = $authorRule->name;
        $auth->add($updateOwnPost);
        // Add updatePost as a child of updateOwnPost for the rule to be checked
        // This means if someone has updateOwnPost, and the rule passes, they can update.
        // The admin will have updatePost directly.
        $auth->addChild($updateOwnPost, $updatePost);


        // Permission to delete any post (typically for admin)
        $deletePost = $auth->createPermission('deletePost');
        $deletePost->description = 'Delete any post';
        $auth->add($deletePost);

        // Permission to delete own post
        $deleteOwnPost = $auth->createPermission('deleteOwnPost');
        $deleteOwnPost->description = 'Delete own post';
        $deleteOwnPost->ruleName = $authorRule->name;
        $auth->add($deleteOwnPost);
        // Add deletePost as a child of deleteOwnPost
        $auth->addChild($deleteOwnPost, $deletePost);


        // --- Roles ---
        // "user" role
        $user = $auth->createRole('user');
        $user->description = 'Registered user';
        $auth->add($user);
        $auth->addChild($user, $createPost);
        $auth->addChild($user, $updateOwnPost);
        $auth->addChild($user, $deleteOwnPost);

        // "admin" role
        $admin = $auth->createRole('admin');
        $admin->description = 'Administrator';
        $auth->add($admin);
        $auth->addChild($admin, $user); // Admin inherits all user permissions
        $auth->addChild($admin, $updatePost); // Admin can update any post
        $auth->addChild($admin, $deletePost); // Admin can delete any post
        
        // Assign admin role to user ID 1 (if exists)
        // This is often done manually or via a separate command after user 1 is created.
        // For this migration, we can try to assign it if user 1 exists.
        // $userModel = \common\models\User::findOne(1);
        // if ($userModel) {
        //     $auth->assign($admin, 1);
        // }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        // Remove roles
        $auth->remove($auth->getRole('admin'));
        $auth->remove($auth->getRole('user'));

        // Remove permissions
        $auth->remove($auth->getPermission('deleteOwnPost'));
        $auth->remove($auth->getPermission('deletePost'));
        $auth->remove($auth->getPermission('updateOwnPost'));
        $auth->remove($auth->getPermission('updatePost'));
        $auth->remove($auth->getPermission('createPost'));
        
        // Remove the rule
        $auth->remove($auth->getRule('isAuthor'));

        // Clear all RBAC data (alternative, more aggressive cleanup)
        // $auth->removeAll();
    }
}

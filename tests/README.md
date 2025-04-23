# Symfony Command Profile Bundle Tests

本目录包含 Symfony Command Profile Bundle 的单元测试。

## 测试结构

- `EventSubscriber/`: 包含对命令执行时间测量事件订阅者的测试
- `DependencyInjection/`: 包含对依赖注入扩展的测试
- `CommandProfileBundleTest.php`: Bundle 类的测试
- `TEST_PLAN.md`: 测试计划和完成情况

## 运行测试

在项目根目录执行以下命令：

```bash
./vendor/bin/phpunit packages/symfony-command-profile-bundle/tests
```

## 测试覆盖率

当前测试覆盖了所有的类和方法，确保了功能的正确性。详情请参阅 `TEST_PLAN.md`。

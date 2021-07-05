<main class="team">
	<?php $i = 2; ?>
	<div class="container-lg">
		<?php foreach (get_sub_field('people') as $item) : ?>
			<?php if ($i % 3 == 0) : ?>
				<div class="row justify-content-lg-end justify-content-center">
				<?php elseif ($i % 2 == 0) : ?>
					<div class="row justify-content-center">
					<?php else : ?>
						<div class="row justify-content-lg-start justify-content-center">
						<?php endif; ?>
						<div class="col-lg-10 col-md-12 col-sm-8">
							<div class="team__item">
								<div class="row">
									<div class="col-md-5">
										<?php if (has_post_thumbnail($item->ID)) : ?>
											<div class="team__item-img">
												<img class="lazy bg-cover" data-src="<?= get_the_post_thumbnail_url($item->ID); ?>" alt="">
											</div>
										<?php endif; ?>
									</div>
									<div class="col-md-7">
										<div class="team__item-text">
											<div class="row justify-content-between">
												<div class="col-xl-8 col-lg-7 col-auto">
													<h2 class="mb-2"><?= get_the_title($item->ID); ?></h2>
													<h5 class="font-secondary text400 my-0"><?= get_field('title', $item->ID); ?></h5>
													<a href="mailto:<?= get_field('email', $item->ID); ?>" class="text400 font-secondary d-inline-block mt-4">
														<i class="fal fa-envelope mr-4"></i><?= get_field('email', $item->ID); ?>
													</a><br />
													<a href="tel:<?= get_field('phone', $item->ID); ?>" class="text400 font-secondary d-inline-block mt-2">
														<i class="fal fa-phone-alt mr-4"></i><?= get_field('phone', $item->ID); ?>
													</a><br />
												</div>
												<div class="col-auto team__item-social d-flex flex-column">
													<?php if (get_field('whatsapp', $item->ID)) : ?>
														<a href="<?= get_field('whatsapp', $item->ID); ?>" class="btn btn__social notched navy"><i class="fab fa-whatsapp"></i></a>
													<?php endif; ?>
													<?php if (get_field('skype', $item->ID)) : ?>
														<a href="<?= get_field('skype', $item->ID); ?>" class="btn btn__social notched navy"><i class="fab fa-skype"></i></a>
													<?php endif; ?>
													<?php if (get_field('linkedin', $item->ID)) : ?>
														<a href="<?= get_field('linkedin', $item->ID); ?>" class="btn btn__social notched navy"><i class="fab fa-linkedin-in"></i></a>
													<?php endif; ?>
												</div>
											</div>
											<?= get_field('short_bio', $item->ID); ?>
											<div class="btns">
												<?php if (get_the_content()) : ?>
													<a href="javascript:void(0)" class="btn btn__default navy team__item-showmore"><?php pll_e('Read more'); ?></a>
												<?php endif; ?>
												<?php if (get_field('calendly', $item->ID)) : ?>
													<a class="btn btn__default pink" href="<?= get_field('calendly', $item->ID) ?>"><u><?php pll_e('Plan een (video)call of meeting'); ?></u></a>
												<?php endif; ?>
											</div>
										</div>
									</div>
									<?php if (get_the_content()) : ?>
										<div class="col-12 team__item-hidden d-none">
											<?php the_content(); ?>
											<?php if (get_field('calendly', $item->ID)) : ?>
												<div class="text-center mt-3 mb-4">
													<a class="btn btn__default pink" href="<?= get_field('calendly', $item->ID) ?>"><u><?php pll_e('Plan een (video)call of meeting'); ?></u></a>
												</div>
											<?php endif; ?>
											<div class="text-center mt-4">
												<a href="javascript:void(0)" class="btn btn__default navy team__item-showless"><?php pll_e('Show less'); ?></a>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</div>
							<div class="text-bubble text-bubble-right-right">
							</div>
						</div>
						</div>
					<?php $i++;
				endforeach; ?>
					</div>
</main>